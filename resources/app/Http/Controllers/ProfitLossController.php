<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AccountGroup;
use App\Models\Party;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class ProfitLossController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $purchases = DB::table('purchase_details')->Sum('total_cost');
        $sales = DB::table('sale_details')->Sum('sale_amount');
        $salesTax = DB::table('sale_tax_details')->Sum('total');
        $expenses = AccountGroup::with(['parties' => function ($query) {
            $query->with('general_vouchers');
        }])->where('id', '=', 6)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('profitloss.index', Compact('purchases', 'sales', 'salesTax', 'expenses', 'company_detail'));
    }

    public function create()
    {
        $shops = Warehouse::OrderBy('id', 'asc')->pluck('name', 'id')->prepend('ALL BRANCHES', '0');
        return view('profitloss.create', Compact('shops'));
    }

    public function store(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $ShopID = $request->get('shop_id');
        $ShopName = Warehouse::where('id', '=', $ShopID)->get();

        if ($ShopID == 0) {
            $purchases = DB::table('sale_details')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->Sum('cost_amount');

            $sales = DB::table('sale_details')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->Sum('sale_amount');
            $salesTax = DB::table('sale_tax_details')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->Sum('total');

            $expenses = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->where('parties.account_group_id', '=', 6)
                ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                ->groupBy('account_head_id')
                ->OrderBy('parties.id')
                ->Sum('debit')
                ->get();

            $expensesCenters = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->where('parties.account_group_id', '=', 27)
                ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                ->groupBy('account_head_id')
                ->OrderBy('parties.id')
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

            $employees = Party::with(['attendance_details' => function ($query) use ($fromDate, $toDate) {
                $query->whereDate('in_time', '>=', $fromDate);
                $query->whereDate('in_time', '<=', $toDate);
            }])->where('account_type', '=', "EMPLOYEES")
                ->get();

            $datetime1 = strtotime($fromDate); // convert to timestamps
            $datetime2 = strtotime($toDate); // convert to timestamps
            $day = (int)(($datetime2 - $datetime1) / 86400); // will give the difference in days , 86400 is the timestamp difference of a day
            $days = $day + 1;

            $company_detail = Setting::where('id', '=', 1)->get();
            return view('profitloss.index', Compact('purchases', 'sales', 'salesTax', 'expenses', 'expensesCenters', 'company_detail', 'fromDate', 'toDate', 'salary', 'employees', 'days', 'ShopName'));
        } else {
            $purchases = DB::table('sale_details')
                ->where('warehouse_id', '=', $ShopID)
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->Sum('cost_amount');

            $sales = DB::table('sale_details')->where('warehouse_id', '=', $ShopID)
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->Sum('sale_amount');

            $salesTax = DB::table('sale_tax_details')
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->Sum('total');

            $expenses = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->where('general_vouchers.warehouse_id', '=', $ShopID)
                ->where('parties.account_group_id', '=', 6)
                ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                ->groupBy('account_head_id')
                ->OrderBy('parties.id')
                ->Sum('debit')
                ->get();


            $expensesCenters = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->where('general_vouchers.warehouse_id', '=', $ShopID)
                ->where('parties.account_group_id', '=', 27)
                ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                ->groupBy('account_head_id')
                ->OrderBy('parties.id')
                ->Sum('debit')
                ->get();


            $salary = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')

                ->whereDate('general_vouchers.date', '>=', $fromDate)
                ->whereDate('general_vouchers.date', '<=', $toDate)
                ->where('general_vouchers.warehouse_id', '=', $ShopID)
                ->where('parties.account_group_id', '=', 8)
                ->select('parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'))
                ->groupBy('account_head_id')
                ->Sum('debit')
                ->get();



            $employees = Party::with(['attendance_details' => function ($query) use ($fromDate, $toDate) {
                $query->whereDate('in_time', '>=', $fromDate);
                $query->whereDate('in_time', '<=', $toDate);
            }])
                ->where('account_type', '=', "EMPLOYEES")
                ->where('shop_id', '=', $ShopID)
                ->get();

            $datetime1 = strtotime($fromDate); // convert to timestamps
            $datetime2 = strtotime($toDate); // convert to timestamps
            $day = (int)(($datetime2 - $datetime1) / 86400); // will give the difference in days , 86400 is the timestamp difference of a day
            $days = $day + 1;

            $company_detail = Setting::where('id', '=', 1)->get();
            return view('profitloss.branchwise', Compact('purchases', 'sales', 'salesTax', 'expenses', 'expensesCenters', 'company_detail', 'fromDate', 'toDate', 'salary', 'employees', 'days', 'ShopName'));
        }
    }
}
