<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class BalanceSheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $asset = DB::table('general_vouchers')
            ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
            ->select('parties.id', 'parties.party_name', DB::raw('SUM(debit) as total'))
            ->groupBy('account_head_id')
            ->where('parties.account_group_id', '=', '3')
            ->get()->toArray();

        $liabilitity = DB::table('general_vouchers')
            ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
            ->select('parties.id', 'parties.party_name', DB::raw('SUM(credit) as total'))
            ->groupBy('account_head_id')
            ->where('parties.account_group_id', '=', '4')
            ->get()->toArray();

        $company_detail = Setting::where('id', '=', 1)->get();
        return view('balance-sheet.index', Compact('asset', 'liabilitity', 'company_detail'));
    }
}
