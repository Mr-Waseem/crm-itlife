<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Party;
use App\Models\Warehouse;
use App\Models\GeneralVoucher;
use DB;

class ExpenseReportController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $Warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('All Branches', '0')->toArray();
        //$ExpenseHeads = ExpenseHead::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $ExpenseHeads = Party::where('account_type', 'EXPENSES')->OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('expense-report.create', Compact('encrypted_token', 'ExpenseHeads', 'Warehouse'));
    }

    public function store(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $ShopID = $request->get('branch_id');
        $ExpenseID = $request->get('expense_id');
        $Type = $request->get('ReportDetail');
        $branch = Warehouse::where('id', '=', $ShopID)->get();


        if ($Type == '1') {
            if ($ShopID == '0') {
                $expenses = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                    ->whereDate('general_vouchers.date', '>=', $fromDate)
                    ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('parties.account_group_id', '=', 6)
                    ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                    ->groupBy('account_head_id')
                    ->Sum('debit')
                    ->get();

                $salary = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                    ->whereDate('general_vouchers.date', '>=', $fromDate)
                    ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('parties.account_group_id', '=', 8)
                    ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                    ->groupBy('account_head_id')
                    ->Sum('debit')
                    ->get();

                $company_detail = Setting::where('id', '=', 1)->get();
                return view('expense-report.index', Compact('expenses', 'company_detail', 'fromDate', 'toDate', 'salary'));
            } else {
                $expenses = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                    ->whereDate('general_vouchers.date', '>=', $fromDate)
                    ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('parties.account_group_id', '=', 6)
                    ->where('general_vouchers.warehouse_id', '=', $ShopID)
                    ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                    ->groupBy('account_head_id')
                    ->Sum('debit')
                    ->get();

                $salary = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                    ->whereDate('general_vouchers.date', '>=', $fromDate)
                    ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('parties.account_group_id', '=', 8)
                    ->where('general_vouchers.warehouse_id', '=', $ShopID)
                    ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                    ->groupBy('account_head_id')
                    ->Sum('debit')
                    ->get();

                $company_detail = Setting::where('id', '=', 1)->get();
                return view('expense-report.index', Compact('expenses', 'company_detail', 'fromDate', 'toDate', 'salary'));
            }
        } else {
            if ($ShopID == '0') {
                //return 'all';
                // $expenses = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                $expenses = GeneralVoucher::join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                    ->whereDate('general_vouchers.date', '>=', $fromDate)
                    ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('general_vouchers.account_head_id', '=', $ExpenseID)
                    ->where('parties.account_group_id', '=', 6)


                    //->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                    //->groupBy('account_head_id')
                    //->Sum('debit')
                    ->get(['parties.*', 'general_vouchers.*']);

                //return $expenses;

                // $salary = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                // ->whereDate('general_vouchers.date', '>=', $fromDate)
                // ->whereDate('general_vouchers.date', '<=', $toDate)
                // ->where('parties.account_group_id', '=', 8)
                // ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                // ->groupBy('account_head_id')
                // ->Sum('debit')
                // ->get();

                $company_detail = Setting::where('id', '=', 1)->get();
                return view('expense-report.detail', Compact('expenses', 'company_detail', 'fromDate', 'toDate'));
            } else {
                //return 'single';
                //     $expenses = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                // ->whereDate('general_vouchers.date', '>=', $fromDate)
                // ->whereDate('general_vouchers.date', '<=', $toDate)
                // ->where('parties.account_group_id', '=', 6)
                // ->where('general_vouchers.warehouse_id', '=', $ShopID)
                // ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                // ->groupBy('account_head_id')
                // ->Sum('debit')
                // ->get();

                $expenses = GeneralVoucher::join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')


                    ->whereDate('general_vouchers.date', '>=', $fromDate)
                    ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('parties.account_group_id', '=', 6)
                    ->where('general_vouchers.warehouse_id', '=', $ShopID)
                    ->where('general_vouchers.account_head_id', '=', $ExpenseID)
                    //->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                    //->groupBy('account_head_id')
                    //->Sum('debit')
                    ->get(['parties.*', 'general_vouchers.*']);

                // $salary = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                // ->whereDate('general_vouchers.date', '>=', $fromDate)
                // ->whereDate('general_vouchers.date', '<=', $toDate)
                // ->where('parties.account_group_id', '=', 8)
                // ->where('general_vouchers.warehouse_id', '=', $ShopID)
                // ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                // ->groupBy('account_head_id')
                // ->Sum('debit')
                // ->get();

                $company_detail = Setting::where('id', '=', 1)->get();
                return view('expense-report.detail-single', Compact('expenses', 'company_detail', 'fromDate', 'toDate', 'salary', 'branch'));
            }
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
}
