<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use DB;

class CreditCashReportController extends Controller
{
    public function index()
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('client-all-report.credit-cash-report.index', Compact('encrypted_token'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $company_detail = Setting::where('id', '=', 1)->get();
        $saletype = $request->get('saletype');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $party = DB::table('general_vouchers')
            ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
            ->whereBetween('date', [$fromDate, $toDate])
            ->selectRaw('parties.party_name')
            ->selectRaw('parties.phone')
            ->selectRaw('sum(debit) as debit')
            ->selectRaw('general_vouchers.id')
            ->selectRaw('sum(credit) as credit')
            ->selectRaw('general_vouchers.id')
            ->groupBy('account_head_id')
            ->where('v_type', '=', $saletype)
            // ->orwhere('v_type', '=', 'Bank Receipt')
            ->get();
        // return $party;

        return view('client-all-report.credit-cash-report.report', Compact('party', 'company_detail', 'fromDate', 'toDate'));
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
}
