<?php

namespace App\Http\Controllers;

use App\Models\AccountGroup3;
use App\Models\LeadDetail;
use App\Models\Party;
use App\Models\User;
use App\Models\VoucherRights;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class LeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $users = User::where('status', 1)->orderBy('name')->get(['id', 'name']);
        $accountGroups = AccountGroup3::orderBy('name')->get(['id', 'name']);

        return view('leads.index', [
            'users' => $users,
            'accountGroups' => $accountGroups,
            'statuses' => LeadDetail::STATUSES,
            'activityTypes' => LeadDetail::ACTIVITY_TYPES,
            'isAdmin' => $this->isAdmin(),
            'canAdd' => $this->hasVoucherRight('ADD'),
            'canEdit' => $this->hasVoucherRight('EDIT'),
        ]);
    }

    public function data(Request $request)
    {
        $query = $this->visibleLeadQuery($request)
            ->with(['latestLeadDetail.assignee', 'openLeadFollowup.assignee'])
            ->orderByDesc('parties.id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->addColumn('lead_status', function (Party $party) {
                $status = optional($party->latestLeadDetail)->lead_status ?: 'New';
                $class = LeadDetail::statusClass($status);

                return '<span class="badge '.$class.'">'.e($status).'</span>';
            })
            ->addColumn('assigned_to_name', function (Party $party) {
                $detail = $party->latestLeadDetail;
                return optional(optional($detail)->assignee)->name
                    ?: optional($detail)->assigned_to_name
                    ?: 'Unassigned';
            })
            ->addColumn('next_follow_up', function (Party $party) {
                $detail = $party->openLeadFollowup;
                if (!$detail) {
                    return '<span class="text-muted">Not scheduled</span>';
                }

                $value = $detail->follow_up_date->format('d M Y');
                if ($detail->follow_up_time) {
                    $value .= ' '.Carbon::parse($detail->follow_up_time)->format('h:i A');
                }

                $class = $this->isOverdue($detail) ? 'text-danger font-weight-bold' : '';
                return '<span class="'.$class.'">'.e($value).'</span>';
            })
            ->addColumn('action', function (Party $party) {
                return '<button type="button" class="btn btn-info btn-sm view-lead" data-id="'.$party->id.'">'
                    .'<i class="fa fa-eye"></i> View</button>';
            })
            ->rawColumns(['lead_status', 'next_follow_up', 'action'])
            ->make(true);
    }

    public function store(Request $request)
    {
        $this->requireVoucherRight('ADD');
        if ($request->filled('phone')) {
            $request->merge(['phone' => $this->toPakistaniMobile($request->phone)]);
        }

        $validated = $request->validate([
            'party_name' => 'required_without:reuse_party_id|string|max:100',
            'phone' => ['required_without:reuse_party_id', 'string', 'regex:/^03[0-9]{9}$/'],
            'party_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:200',
            'follow_up_date' => 'required|date|after_or_equal:today',
            'follow_up_time' => 'nullable|date_format:H:i',
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('status', 1)],
            'remarks' => 'nullable|string|max:2000',
            'reuse_party_id' => 'nullable|exists:parties,id',
            'force_duplicate' => 'nullable|boolean',
        ]);

        $normalizedPhone = isset($validated['phone']) ? $this->normalizePhone($validated['phone']) : null;
        if (!$request->filled('reuse_party_id') && !$request->boolean('force_duplicate')) {
            $matches = Party::where('phone_normalized', $normalizedPhone)
                ->whereNotNull('phone_normalized')
                ->get(['id', 'party_name', 'phone', 'role']);

            if ($matches->isNotEmpty()) {
                return response()->json([
                    'message' => 'A party with this phone number already exists.',
                    'duplicates' => $matches,
                ], 409);
            }
        }

        $assignee = $this->resolveAssignee($validated['assigned_to'] ?? null);

        $party = DB::transaction(function () use ($request, $validated, $normalizedPhone, $assignee) {
            if ($request->filled('reuse_party_id')) {
                $party = Party::lockForUpdate()->findOrFail($validated['reuse_party_id']);
                if ($party->leadDetails()->exists() && !$this->isAdmin()) {
                    abort_unless(optional($party->latestLeadDetail)->assigned_to === Auth::id(), 403);
                }
            } else {
                $party = Party::create([
                    'shop_id' => config('app.party_shop_id', 2),
                    'party_name' => $validated['party_name'],
                    'phone' => $validated['phone'],
                    'phone_normalized' => $normalizedPhone,
                    'party_email' => isset($validated['party_email']) ? strtolower(trim($validated['party_email'])) : null,
                    'address' => $validated['address'] ?? null,
                    'role' => 'Lead',
                    'type' => 'Lead',
                    'status' => 1,
                    'created_by' => Auth::id(),
                    'updated_by' => Auth::id(),
                ]);
            }

            $latest = $party->leadDetails()->latest('id')->first();
            $this->cancelOpenFollowups($party->id);

            LeadDetail::create([
                'party_id' => $party->id,
                'lead_status' => optional($latest)->lead_status ?: 'New',
                'activity_type' => $latest ? 'Note' : 'Lead Created',
                'follow_up_date' => $validated['follow_up_date'],
                'follow_up_time' => $validated['follow_up_time'] ?? null,
                'assigned_to' => $assignee->id,
                'assigned_to_name' => $assignee->name,
                'remarks' => $validated['remarks'] ?? null,
                'created_by' => Auth::id(),
            ]);

            return $party;
        });

        return response()->json(['message' => 'Lead saved successfully.', 'party_id' => $party->id]);
    }

    public function duplicates(Request $request)
    {
        $this->requireVoucherRight('ADD');
        $request->validate(['phone' => 'required|string|max:100']);
        $normalized = $this->normalizePhone($request->phone);

        return response()->json(Party::where('phone_normalized', $normalized)
            ->whereNotNull('phone_normalized')
            ->get(['id', 'party_name', 'phone', 'role']));
    }

    public function events(Request $request)
    {
        $validated = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
            'scope' => 'nullable|in:mine,all',
            'assigned_to' => 'nullable|integer|exists:users,id',
            'status' => ['nullable', Rule::in(array_keys(LeadDetail::STATUSES))],
        ]);

        $start = Carbon::parse($validated['start'])->startOfDay();
        $end = Carbon::parse($validated['end'])->startOfDay();

        $query = LeadDetail::query()
            ->open()
            ->with(['party.latestLeadDetail', 'assignee'])
            ->whereDate('follow_up_date', '>=', $start->toDateString())
            ->whereDate('follow_up_date', '<', $end->toDateString());

        if (!$this->isAdmin() || ($validated['scope'] ?? null) === 'mine') {
            $query->where('assigned_to', Auth::id());
        } elseif (!empty($validated['assigned_to'])) {
            $query->where('assigned_to', $validated['assigned_to']);
        }

        $events = $query->get()->filter(function (LeadDetail $detail) use ($validated) {
            $currentStatus = optional($detail->party->latestLeadDetail)->lead_status ?: $detail->lead_status;
            return empty($validated['status']) || $currentStatus === $validated['status'];
        })->map(function (LeadDetail $detail) {
            $currentStatus = optional($detail->party->latestLeadDetail)->lead_status ?: $detail->lead_status;
            $overdue = $this->isOverdue($detail);
            $start = $detail->follow_up_date->format('Y-m-d');
            $allDay = empty($detail->follow_up_time);
            if (!$allDay) {
                $start = Carbon::parse($start.' '.$detail->follow_up_time, config('app.timezone'))
                    ->toIso8601String();
            }

            return [
                'id' => 'followup-'.$detail->id,
                'title' => $detail->party->party_name.' • '.$detail->party->phone,
                'start' => $start,
                'allDay' => $allDay,
                'className' => array_values(array_filter([
                    LeadDetail::statusClass($currentStatus),
                    $overdue ? 'lead-overdue' : null,
                ])),
                'party_id' => $detail->party_id,
                'lead_detail_id' => $detail->id,
                'lead_status' => $currentStatus,
                'assigned_to' => $detail->assigned_to,
                'assigned_to_name' => optional($detail->assignee)->name ?: $detail->assigned_to_name,
                'is_overdue' => $overdue,
            ];
        })->values();

        return response()->json($events);
    }

    public function show(Party $party)
    {
        $this->ensureVisible($party);
        $party->load(['leadDetails' => function ($query) {
            $query->with(['assignee', 'creator'])->latest('id');
        }, 'latestLeadDetail.assignee', 'openLeadFollowup.assignee']);

        return response()->json([
            'party' => [
                'id' => $party->id,
                'party_name' => $party->party_name,
                'phone' => $party->phone,
                'party_email' => $party->party_email,
                'address' => $party->address,
                'role' => $party->role,
            ],
            'current_status' => optional($party->latestLeadDetail)->lead_status,
            'assigned_to' => optional($party->latestLeadDetail)->assigned_to,
            'open_follow_up' => $party->openLeadFollowup ? $this->detailPayload($party->openLeadFollowup) : null,
            'history' => $party->leadDetails->map(fn (LeadDetail $detail) => $this->detailPayload($detail))->values(),
        ]);
    }

    public function update(Request $request, Party $party)
    {
        $this->requireVoucherRight('EDIT');
        $this->ensureVisible($party);
        $request->merge(['phone' => $this->toPakistaniMobile($request->phone)]);

        $validated = $request->validate([
            'party_name' => 'required|string|max:100',
            'phone' => ['required', 'string', 'regex:/^03[0-9]{9}$/'],
            'party_email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:200',
        ]);

        $party->update([
            'party_name' => $validated['party_name'],
            'phone' => $validated['phone'],
            'phone_normalized' => $this->normalizePhone($validated['phone']),
            'party_email' => isset($validated['party_email']) ? strtolower(trim($validated['party_email'])) : null,
            'address' => $validated['address'] ?? null,
            'updated_by' => Auth::id(),
        ]);

        return response()->json(['message' => 'Lead contact updated successfully.']);
    }

    public function followUp(Request $request, Party $party)
    {
        $this->requireVoucherRight('EDIT');
        $this->ensureVisible($party);

        $validated = $request->validate([
            'lead_status' => ['required', Rule::in(array_keys(LeadDetail::STATUSES))],
            'activity_type' => ['required', Rule::in(LeadDetail::ACTIVITY_TYPES)],
            'follow_up_date' => 'nullable|date|after_or_equal:today|required_with:follow_up_time',
            'follow_up_time' => 'nullable|date_format:H:i',
            'assigned_to' => ['nullable', Rule::exists('users', 'id')->where('status', 1)],
            'remarks' => 'nullable|string|max:2000',
            'reschedule_only' => 'nullable|boolean',
        ]);

        if ($validated['lead_status'] === 'Converted') {
            return response()->json(['message' => 'Use guided conversion to convert this lead.'], 422);
        }
        if ($validated['lead_status'] === 'Lost' && !empty($validated['follow_up_date'])) {
            return response()->json(['message' => 'A Lost lead cannot have an open follow-up.'], 422);
        }

        $assignee = $this->resolveAssignee($validated['assigned_to'] ?? null);

        DB::transaction(function () use ($request, $party, $validated, $assignee) {
            Party::whereKey($party->id)->lockForUpdate()->first();
            $open = LeadDetail::where('party_id', $party->id)->open()->lockForUpdate()->get();

            foreach ($open as $detail) {
                $detail->update($request->boolean('reschedule_only')
                    ? ['cancelled_at' => now()]
                    : ['completed_at' => now()]);
            }

            LeadDetail::create([
                'party_id' => $party->id,
                'lead_status' => $validated['lead_status'],
                'activity_type' => $validated['activity_type'],
                'follow_up_date' => $validated['follow_up_date'] ?? null,
                'follow_up_time' => $validated['follow_up_time'] ?? null,
                'assigned_to' => $assignee->id,
                'assigned_to_name' => $assignee->name,
                'remarks' => $validated['remarks'] ?? null,
                'supersedes_id' => optional($open->last())->id,
                'created_by' => Auth::id(),
            ]);
        });

        return response()->json(['message' => 'Lead activity saved successfully.']);
    }

    public function convert(Request $request, Party $party)
    {
        $this->requireVoucherRight('EDIT');
        $this->ensureVisible($party);

        $validated = $request->validate([
            'account_group_id3' => 'required|exists:account_groups3,id',
            'registration_type' => 'required|in:Registered,Un Registered',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'show_products' => 'required|boolean',
            'remarks' => 'nullable|string|max:2000',
        ]);

        DB::transaction(function () use ($party, $validated) {
            $party = Party::whereKey($party->id)->lockForUpdate()->firstOrFail();
            if ($party->role !== 'Lead') {
                abort(422, 'Only a Lead party can be converted to Customer.');
            }

            $group = AccountGroup3::findOrFail($validated['account_group_id3']);
            $count = Party::where('account_group_id3', $group->id)->lockForUpdate()->count() + 1;
            $latest = LeadDetail::where('party_id', $party->id)->latest('id')->first();

            $party->update([
                'code' => (int) ($group->code.$count),
                'account_type' => 'CUSTOMER',
                'account_group_id' => $group->account_group1_id,
                'account_group_id2' => $group->account_group2_id,
                'account_group_id3' => $group->id,
                'party_email' => strtolower(trim($validated['email'])),
                'role' => 'Customer',
                'type' => $validated['registration_type'],
                'show_products' => $validated['show_products'],
                'status' => 1,
                'updated_by' => Auth::id(),
            ]);

            User::create([
                'name' => $party->party_name,
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
                'showpassword' => '',
                'shop_id' => $party->shop_id,
                'biller_id' => 0,
                'phone' => $party->phone,
                'address' => $party->address,
                'role' => 'Normal User',
                'status' => 1,
                'warehouse_id' => Auth::user()->warehouse_id,
                'party_id' => $party->id,
                'type' => 'CUSTOMER',
            ]);

            LeadDetail::where('party_id', $party->id)->open()->update(['cancelled_at' => now()]);
            LeadDetail::create([
                'party_id' => $party->id,
                'lead_status' => 'Converted',
                'activity_type' => 'Conversion',
                'assigned_to' => optional($latest)->assigned_to ?: Auth::id(),
                'assigned_to_name' => optional($latest)->assigned_to_name ?: Auth::user()->name,
                'remarks' => $validated['remarks'] ?? null,
                'created_by' => Auth::id(),
            ]);
        });

        return response()->json(['message' => 'Lead converted to Customer successfully.']);
    }

    private function visibleLeadQuery(Request $request): Builder
    {
        $query = Party::query()->whereHas('leadDetails');

        if (!$this->isAdmin() || $request->input('scope') === 'mine') {
            $query->whereRaw('(SELECT assigned_to FROM lead_details WHERE lead_details.party_id = parties.id ORDER BY lead_details.id DESC LIMIT 1) = ?', [Auth::id()]);
        } elseif ($request->filled('assigned_to')) {
            $query->whereRaw('(SELECT assigned_to FROM lead_details WHERE lead_details.party_id = parties.id ORDER BY lead_details.id DESC LIMIT 1) = ?', [$request->integer('assigned_to')]);
        }

        if ($request->filled('status')) {
            $query->whereRaw('(SELECT lead_status FROM lead_details WHERE lead_details.party_id = parties.id ORDER BY lead_details.id DESC LIMIT 1) = ?', [$request->input('status')]);
        }

        return $query;
    }

    private function ensureVisible(Party $party): void
    {
        abort_unless($party->leadDetails()->exists(), 404);
        if (!$this->isAdmin()) {
            abort_unless(optional($party->latestLeadDetail)->assigned_to === Auth::id(), 403);
        }
    }

    private function resolveAssignee(?int $requestedId): User
    {
        $id = $this->isAdmin() && $requestedId ? $requestedId : Auth::id();
        return User::where('status', 1)->findOrFail($id);
    }

    private function cancelOpenFollowups(int $partyId): void
    {
        LeadDetail::where('party_id', $partyId)->open()->update(['cancelled_at' => now()]);
    }

    private function normalizePhone(string $phone): ?string
    {
        $normalized = preg_replace('/\D+/', '', $phone);
        if (str_starts_with($normalized, '0092')) {
            $normalized = substr($normalized, 2);
        } elseif (str_starts_with($normalized, '03') && strlen($normalized) === 11) {
            $normalized = '92'.substr($normalized, 1);
        }

        return $normalized ?: null;
    }

    private function toPakistaniMobile(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);
        if (str_starts_with($digits, '0092')) {
            $digits = '0'.substr($digits, 4);
        } elseif (str_starts_with($digits, '92')) {
            $digits = '0'.substr($digits, 2);
        } elseif (str_starts_with($digits, '3')) {
            $digits = '0'.$digits;
        }

        return substr($digits, 0, 11);
    }

    private function isOverdue(LeadDetail $detail): bool
    {
        if (!$detail->follow_up_date || $detail->completed_at || $detail->cancelled_at) {
            return false;
        }

        if (!$detail->follow_up_time) {
            return $detail->follow_up_date->lt(today(config('app.timezone')));
        }

        return Carbon::parse(
            $detail->follow_up_date->format('Y-m-d').' '.$detail->follow_up_time,
            config('app.timezone')
        )->lt(now(config('app.timezone')));
    }

    private function detailPayload(LeadDetail $detail): array
    {
        return [
            'id' => $detail->id,
            'lead_status' => $detail->lead_status,
            'status_class' => LeadDetail::statusClass($detail->lead_status),
            'activity_type' => $detail->activity_type,
            'follow_up_date' => optional($detail->follow_up_date)->format('Y-m-d'),
            'follow_up_time' => $detail->follow_up_time,
            'assigned_to' => $detail->assigned_to,
            'assigned_to_name' => optional($detail->assignee)->name ?: $detail->assigned_to_name,
            'remarks' => $detail->remarks,
            'completed_at' => optional($detail->completed_at)->toDateTimeString(),
            'cancelled_at' => optional($detail->cancelled_at)->toDateTimeString(),
            'created_at' => optional($detail->created_at)->toDateTimeString(),
            'created_by_name' => optional($detail->creator)->name,
            'is_overdue' => $this->isOverdue($detail),
        ];
    }

    private function isAdmin(): bool
    {
        return strcasecmp((string) Auth::user()->role, 'Admin') === 0;
    }

    private function hasVoucherRight(string $right): bool
    {
        return VoucherRights::where('user_id', Auth::id())
            ->where('voucher_name', 'LEADS')
            ->where('right_name', $right)
            ->exists();
    }

    private function requireVoucherRight(string $right): void
    {
        abort_unless($this->hasVoucherRight($right), 403, 'Insufficient Permission.');
    }
}
