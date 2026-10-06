<?php

namespace App\Http\Controllers;

use App\Models\AccountHead;
use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\GeneralVoucher;
use App\Models\Setting;
use App\Models\LedgerDetailWise;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class ClientAllReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // with all bank accounts
        $HeadsAdmin = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Account', '')->toArray();

        // Manager
        $HeadsManager = Party::where('account_group_id', '=', 1)
            ->orwhere('account_group_id', '=', 2)
            ->orwhere('account_group_id', '=', 8)
            ->orwhere('account_group_id', '=', 9)
            ->orwhere('account_group_id', '=', 53)
            ->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Account', '')->toArray();

        // only customers
        $Heads = Party::where('id', '!=', 1)->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Account', '')->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('client-all-report.index', Compact('encrypted_token', 'Heads', 'HeadsAdmin', 'HeadsManager'));
    }

    public function ledgerallpartyDetail()
    {
        return view('client-all-report.ledger-all-party-detail.index');
    }

    public function LedgerAllPartyReport(Request $request)
    {
        $this->validate($request, [
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $GeneralVoucher = GeneralVoucher::with('parties')->orderBy('date', 'asc')
            ->whereDate('general_vouchers.date', '>=', $fromDate)
            ->whereDate('general_vouchers.date', '<=', $toDate)
            ->get();
        $items = LedgerDetailWise::with('products')->OrderBy('ledger_detail_wise.date', 'asc')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('client-all-report.ledger-all-party-detail.report', Compact('GeneralVoucher', 'company_detail', 'fromDate', 'toDate'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
    }

    public function report(Request $request)
    {
        $this->validate($request, [
            'head_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $HeadID = $request->get('head_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $ReportDetail = $request->get('ReportDetail');
        $LastDayDate = date('Y-m-d', strtotime($fromDate . ' -1 day'));

        $company_detail = Setting::where('id', '=', 1)->get();
        $party = Party::where('id', '=', $HeadID)->get();
        
        if ($ReportDetail == 1) {
            $GeneralVoucher = GeneralVoucher::with('banks')->with('other_parties')
                ->orderBy('date', 'asc')
                ->OrderBy('id', 'asc')
                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->where('account_head_id', '=', $HeadID)->get();

            $openingBalance = LedgerDetailWise::whereDate('date', '>=', '2019-01-01')
                ->whereDate('date', '<', $fromDate)
                ->where('ledger_detail_wise.party_id', '=', $HeadID)
                ->select(DB::raw('SUM(ledger_detail_wise.credit) as OpeningIN, SUM(ledger_detail_wise.debit) as OpeningOUT'))
                ->get();

            return view('client-all-report.report', Compact('GeneralVoucher', 'company_detail', 'party', 'fromDate', 'toDate', 'openingBalance'));
        }

        if ($ReportDetail == 2) {
            $items = LedgerDetailWise::with('products')->with('other_parties')
                ->OrderBy('ledger_detail_wise.date', 'asc')
                ->OrderBy('ledger_detail_wise.id', 'asc')
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('ledger_detail_wise.party_id', '=', $HeadID)
                ->get();

            $openingBalance = LedgerDetailWise::whereDate('date', '>=', '2019-01-01')
                ->whereDate('date', '<', $fromDate)
                ->where('ledger_detail_wise.party_id', '=', $HeadID)
                ->select(DB::raw('SUM(ledger_detail_wise.credit) as OpeningIN, SUM(ledger_detail_wise.debit) as OpeningOUT'))
                ->get();

            return view('ledger-detail-wise.report-albash', Compact('items', 'company_detail', 'party', 'fromDate', 'toDate', 'openingBalance'));
        }

        return view('client-all-report.report', Compact('GeneralVoucher', 'company_detail', 'party', 'fromDate', 'toDate'));
    }

    public function ledgerallpartyIndex()
    {
        $Warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('All Branches', '0')->toArray();
        $accountHeads = AccountHead::orderBy('id')->pluck('name','id')->prepend('All Accounts', '0')->toArray();
        return view('ledger-all-party.index', Compact('Warehouse','accountHeads'));
    }

    public function LedgerAllParty(Request $request)
    {
        $company_detail = Setting::where('id', '=', 1)->get();
        $saletype = $request->get('saletype');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $account_id = $request->get('account_id');

        if ($account_id == "0") {
            $party = DB::table('general_vouchers')
                ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->selectRaw('parties.party_name')
                ->selectRaw('parties.phone')
                ->selectRaw('parties.address')
                ->selectRaw('sum(debit) as debit')
                ->selectRaw('general_vouchers.id')
                ->selectRaw('sum(credit) as credit')
                ->selectRaw('general_vouchers.id')
                ->groupBy('account_head_id')
                ->OrderBy('parties.party_name')
                // ->where('parties.account_group_id', '=', 1) //customer
                // ->orwhere('parties.account_group_id', '=', 2) //supplier
                // ->orwhere('parties.account_group_id', '=', 7) //both
                ->where('parties.id', '!=', '1')
                ->get();

            return view('ledger-all-party.report', Compact('party', 'company_detail', 'fromDate', 'toDate'));
        } else {

            $party = DB::table('general_vouchers')
                ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')

                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->selectRaw('parties.party_name')
                ->selectRaw('parties.phone')
                ->selectRaw('parties.address')
                ->selectRaw('sum(debit) as debit')
                ->selectRaw('general_vouchers.id')
                ->selectRaw('sum(credit) as credit')
                ->selectRaw('general_vouchers.id')
                ->groupBy('account_head_id')
                ->OrderBy('parties.party_name')
                // ->where(function ($query) {
                //     $query->Orwhere('parties.account_group_id', '=', 1);
                //     $query->Orwhere('parties.account_group_id', '=', 2);
                //     $query->Orwhere('parties.account_group_id', '=', 7);
                // })
                // ->where('parties.id', '!=', '1')
                ->where('parties.account_group_id',$account_id)
                ->get();



            $branch = Warehouse::where('id', '=', $account_id)->get();

            return view('ledger-all-party.report', Compact('party', 'company_detail', 'fromDate', 'toDate', 'branch'));
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function ledgerForTrialBalance($id)
    {

        $GeneralVoucher = GeneralVoucher::with('banks')->orderBy('date', 'asc')->where('account_head_id', '=', $id)->get();
        $items = LedgerDetailWise::with('products')->OrderBy('ledger_detail_wise.date', 'asc')
            ->where('ledger_detail_wise.party_id', '=', $id)
            ->get();

        $party = Party::where('id', '=', $id)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('client-all-report.ledger-for-trial.index', Compact('GeneralVoucher', 'company_detail', 'party'));
    }
}