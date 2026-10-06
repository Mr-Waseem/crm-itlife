<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Warehouse;
use App\Models\Quotation;
use App\Models\QuotationDetails;
use App\Models\QuotationMilestone;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class QuotationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
        $last = Quotation::where('warehouse_id', Auth::User()->warehouse_id)
            ->orderBy('id', 'desc')
            ->first();
        if ($last) {
            $codes = (int) $last->voucher_no + 1;
        }

        $products = Product::select(DB::raw('
                CONCAT(`id`, "_", `code`, "_", `product_name`) AS `id`,
                CONCAT(`code`, "-", `product_name`) AS `product_name`
            '))
            ->orderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');

        $customers = Party::select(DB::raw('CONCAT(`code`, "-", `party_name`) AS `party_name`, `id`'))
            ->where('role', '=', 'Customer')
            ->orderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

        $partiesData = Party::where('role', '=', 'Customer')
            ->get(['id', 'party_name', 'code', 'address', 'city', 'phone'])
            ->keyBy('id');

        $usertype = Auth::User()->role;
        if ($usertype == 'Admin') {
            $warehouse = Warehouse::orderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
        } else {
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        }

        $paymentOptions = [
            '' => 'Select Payment %',
            '10' => '10%',
            '20' => '20%',
            '25' => '25%',
            '30' => '30%',
            '40' => '40%',
            '50' => '50%',
            '60' => '60%',
            '70' => '70%',
            '75' => '75%',
            '80' => '80%',
            '90' => '90%',
            '100' => '100%',
        ];

        $timeframeOptions = ['' => 'Select Timeframe'];
        for ($i = 0; $i <= 90; $i++) {
            $timeframeOptions[(string) $i] = $i . ' Working Days';
        }

        return view('quotation.index', compact(
            'codes',
            'customers',
            'products',
            'warehouse',
            'partiesData',
            'paymentOptions',
            'timeframeOptions'
        ));
    }

    public function Warehouse_voucherNo(Request $request)
    {
        $codes = 1;
        $code = Quotation::where('warehouse_id', $request->warehouseID)
            ->orderBy('id', 'desc')
            ->first();
        if ($code) {
            $codes = (int) $code->voucher_no + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function partyInfo(Request $request)
    {
        $party = Party::where('id', $request->party_id)->first(['id', 'party_name', 'code', 'address', 'city', 'phone']);
        return Response::json(['data' => $party]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
            'warehouse_id' => 'required',
            'party_id' => 'required',
        ], [
            'date.required' => 'The Quotation Date field is required.',
            'warehouse_id.required' => 'Please select Warehouse.',
            'party_id.required' => 'Please select Party.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $hasProducts = isset($request->product_name) && is_array($request->product_name) && count(array_filter($request->product_name)) > 0;
        if (!$hasProducts) {
            return redirect()->back()->with('failure_message', 'Please add at least 1 product line')->withInput();
        }

        $header = [
            'voucher_no' => $request->voucher_no,
            'date' => $request->date,
            'valid_to' => $request->valid_to,
            'warehouse_id' => $request->warehouse_id,
            'party_id' => $request->party_id,
            'atten' => $request->atten,
            'subject' => $request->subject,
            'features' => $request->features,
            'deadline_days' => $request->deadline_days,
            'warranty_months' => $request->warranty_months,
            'remarks' => $request->remarks,
            'updated_by' => Auth::User()->id,
        ];

        if ($request->update_voucher_id != null) {
            if (Auth::User()->role != 'Admin') {
                $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                    ->where('voucher_name', 'QUOTATION')
                    ->where('right_name', 'EDIT')
                    ->first();
                if (!$voucherRight) {
                    return redirect()->back()->with('access_granted', 'Insufficient Permission.');
                }
            }

            $quotation = Quotation::where('id', $request->update_voucher_id)
                ->where('warehouse_id', $request->warehouse_id)
                ->first();

            if (!$quotation) {
                return redirect()->back()->with('failure_message', 'Quotation Not Exist!');
            }

            $quotation->update($header);
            QuotationDetails::where('quotation_id', $quotation->id)->delete();
            QuotationMilestone::where('quotation_id', $quotation->id)->delete();
            $this->saveDetails($request, $quotation);
            $this->saveMilestones($request, $quotation);

            Session::flash('flash_message', 'Quotation Updated Successfully!');
            return redirect('quotation');
        }

        if (Auth::User()->role != 'Admin') {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'QUOTATION')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
        }

        $exists = Quotation::where('warehouse_id', $request->warehouse_id)
            ->where('voucher_no', $request->voucher_no)
            ->first();
        if ($exists) {
            return redirect()->back()->with('failure_message', 'Voucher No Already Exist!')->withInput();
        }

        $header['created_by'] = Auth::User()->id;
        $quotation = Quotation::create($header);
        $this->saveDetails($request, $quotation);
        $this->saveMilestones($request, $quotation);

        Session::flash('flash_message', 'Quotation Added Successfully!');
        return redirect('quotation');
    }

    private function saveDetails(Request $request, Quotation $quotation)
    {
        $names = $request->product_name ?? [];
        $count = count($names);
        for ($i = 0; $i < $count; $i++) {
            $name = trim($names[$i] ?? '');
            if ($name === '') {
                continue;
            }
            QuotationDetails::create([
                'quotation_id' => $quotation->id,
                'warehouse_id' => $request->warehouse_id,
                'party_id' => $request->party_id,
                'product_id' => $request->product_id[$i] ?? null,
                'product_name' => $name,
                'description' => $request->description[$i] ?? null,
                'line_date' => $request->line_date[$i] ?? null,
            ]);
        }
    }

    private function saveMilestones(Request $request, Quotation $quotation)
    {
        $modules = $request->module_name ?? [];
        $count = count($modules);
        for ($i = 0; $i < $count; $i++) {
            $module = trim($modules[$i] ?? '');
            if ($module === '') {
                continue;
            }
            QuotationMilestone::create([
                'quotation_id' => $quotation->id,
                'module_name' => $module,
                'payment_percent' => (int) ($request->payment_percent[$i] ?? 0),
                'timeframe_days' => (int) ($request->timeframe_days[$i] ?? 0),
            ]);
        }
    }

    private function loadQuotationPayload($quotation)
    {
        return Quotation::with(['quotation_details', 'milestones', 'party:id,party_name,address,city,code,phone'])
            ->where('id', $quotation->id)
            ->first();
    }

    public function editData(Request $request)
    {
        $editdata = Quotation::where('warehouse_id', $request->warehouseID)
            ->where('voucher_no', $request->voucher_no)
            ->first();

        if ($editdata) {
            return Response::json(['data' => $this->loadQuotationPayload($editdata)]);
        }

        return Response::json(['data' => '']);
    }

    public function LoadPreviousData(Request $request)
    {
        $prevoucher = Quotation::where('voucher_no', '<', $request->voucher_no)
            ->where('warehouse_id', $request->warehouseID)
            ->max('voucher_no');

        if ($prevoucher) {
            $edit = Quotation::where('voucher_no', $prevoucher)
                ->where('warehouse_id', $request->warehouseID)
                ->first();
            return Response::json(['data' => $this->loadQuotationPayload($edit)]);
        }

        return Response::json(['data' => '']);
    }

    public function LoadNextData(Request $request)
    {
        $nextvoucher = Quotation::where('warehouse_id', $request->warehouseID)
            ->where('voucher_no', '>', $request->voucher_no)
            ->min('voucher_no');

        if ($nextvoucher) {
            $edit = Quotation::where('voucher_no', $nextvoucher)
                ->where('warehouse_id', $request->warehouseID)
                ->first();
            return Response::json(['data' => $this->loadQuotationPayload($edit)]);
        }

        return Response::json(['data' => '']);
    }

    public function DeleteVoucher(Request $request)
    {
        if (Auth::User()->role != 'Admin') {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'QUOTATION')
                ->where('right_name', 'DELETE')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
        }

        $quotation = Quotation::where('warehouse_id', $request->delete_warehouse_id)
            ->where('voucher_no', $request->delete_voucher_no)
            ->first();

        if ($quotation) {
            QuotationDetails::where('quotation_id', $quotation->id)->delete();
            QuotationMilestone::where('quotation_id', $quotation->id)->delete();
            $quotation->delete();
            Session::flash('flash_message', 'Quotation Deleted Successfully!');
            return redirect('quotation');
        }

        return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
    }

    public function PrintVoucher(Request $request)
    {
        $uploadDir = base_path('upload/quotation');
        if (!File::isDirectory($uploadDir)) {
            File::makeDirectory($uploadDir, 0755, true);
        }
        File::cleanDirectory($uploadDir);

        $quotation = Quotation::where('warehouse_id', $request->warehouseID)
            ->where('voucher_no', $request->voucher_no)
            ->first();

        if (!$quotation) {
            return false;
        }

        $quotation->load(['party', 'creator', 'quotation_details', 'milestones']);
        $warehouseTitle = Setting::first();
        $warehouse = Warehouse::where('id', $request->warehouseID)->first();

        $pdf = PDF::loadView('quotation.invoice', compact(
            'quotation',
            'warehouseTitle',
            'warehouse'
        ));

        $fileName = 'quotation-' . $request->voucher_no . '.pdf';
        $pdf->save($uploadDir . '/' . $fileName);
        return $fileName;
    }
}
