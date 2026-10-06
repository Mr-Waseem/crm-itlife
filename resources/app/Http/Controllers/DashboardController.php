<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use App\Models\Party;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use App\Models\Sales;
use App\Models\SaleTax;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $company_detail = Setting::where('id', '=', 1)->get();
        $party = Party::count();
        $supplier = Supplier::count();
        $product = Product::count();
        $user = User::count();
        $purchases = DB::table('purchase_details')->Sum('total_cost');
        $sales = DB::table('sale_details')->Sum('sale_amount');
        $Gstsales = DB::table('sale_tax_details')->Sum('total');
        $expenses = DB::table('expenses')->Sum('expense');
        //$todaySale = DB::table('sale_details')->whereDate('created_at', DB::raw('CURDATE()'))->get();
        $todayPurchase = PurchaseDetail::whereDate('created_at', DB::raw('CURDATE()'))->get();
        $totalPurchase = 0;
        foreach ($todayPurchase as $allpurchases) {
            $totalPurchase = $totalPurchase + $allpurchases->total_cost;
        }

        $todaySale = SaleDetail::whereDate('created_at', DB::raw('CURDATE()'))->get();
        $totalSale = 0;
        foreach ($todaySale as $allsales) {
            $totalSale = $totalSale + $allsales->total_cost;
        }


        // $sale = SaleDetail::select(DB::raw("SUM(sale_amount) as count"))
        // ->orderBy("created_at")
        // ->groupBy(DB::raw("month(created_at)"))
        // ->get()->toArray();

        //$sale = SaleDetail::select(DB::raw("SUM(sale_amount) as count"))
        // $sale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
        // ->orderBy("sale_details.created_at")
        // ->where('sales.sale_type', '=', 'Cash Sale')
        // ->groupBy(DB::raw("month(sale_details.created_at)"))
        // ->get([
        //    DB::raw('YEAR(sale_details.created_at) as year'),
        //                     DB::raw('MONTHNAME(sale_details.created_at) as month'),
        //                     DB::raw('SUM(sale_details.sale_amount) as total')
        //                 ]);
        //    return $sale;

        $monthlyCashSale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.sale_type', '=', 'Cash Sale')
            ->groupBy(DB::raw("month(sale_details.created_at)"))
            ->get([
                DB::raw('YEAR(sale_details.created_at) as year'),
                DB::raw('MONTHNAME(sale_details.created_at) as month'),
                DB::raw('SUM(sale_details.sale_amount) as total')
            ]);

        // return $monthlyCashSale;
        // $test = DB::table('sale_details')
        //             ->groupBy('created_at')->get([
        //                         DB::raw('YEAR(created_at) as year'),
        //                         DB::raw('month(created_at) as month'),
        //                         DB::raw('SUM(sale_amount) as total')
        //                     ]);
        //    return $test;


        $monthlyCreditSale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->groupBy(DB::raw("month(sales.date)"))
            ->OrderBy('sales.date', 'asc')
            //->where('sales.sale_type', '=', 'Credit Sale')
            ->get([
                DB::raw('YEAR(sales.date) as year'),
                DB::raw('MONTHNAME(sales.date) as month'),
                DB::raw('SUM(sale_details.sale_amount) as total'),
                'sales.sale_type as type'
            ]);

        // return $monthlyCreditSale;

        // $monthlyCreditSale = SaleDetail::groupBy(DB::raw("month(sale_details.created_at)"))->get([
        //          DB::raw('YEAR(sale_details.created_at) as year'),
        //          DB::raw('MONTHNAME(sale_details.created_at) as month'),
        //          DB::raw('SUM(sale_details.sale_amount) as total')
        //      ]);
        //return $sale;
        //return $monthlyCreditSale;
        $GSTSales = SaleTax::join('sale_tax_details', 'sale_tax_details.sale_id', '=', 'sale_taxes.id')
            ->groupBy(DB::raw("month(sale_tax_details.created_at)"))
            // ->where('sale_taxes.sale_type', '=', 'Credit Sale')
            ->get([
                DB::raw('YEAR(sale_tax_details.created_at) as year'),
                DB::raw('MONTHNAME(sale_tax_details.created_at) as month'),
                DB::raw('SUM(sale_tax_details.total) as total')
            ]);

        //return $GSTSale;
        //$banks = Party::OrderBy('party_name', 'asc')->where('account_type', 'BANK')->get();
        $banks = Party::join('general_vouchers', 'general_vouchers.account_head_id', '=', 'parties.id')
            ->where('parties.account_type', '=', 'BANK')
            ->Orwhere('parties.id', '=', 1)
            // ->groupBy(DB::raw("month(sale_details.created_at)"))
            ->groupBy('general_vouchers.account_head_id')
            ->get([
                DB::raw('parties.party_name'),
                DB::raw('SUM(general_vouchers.debit) as debit'),
                DB::raw('SUM(general_vouchers.credit) as credit')
            ]);
        //return $banks;
        //////////DAY WISE STATUS//////////////////////////////////////////////////////////////
        $now = Carbon::now();
        $start = $now->startOfWeek();
        $end = $now->endOfWeek();
        $dailyCashSale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.sale_type', '=', 'Cash Sale')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('sale_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(sale_details.created_at)"))
            ->get([
                DB::raw('DATE(sale_details.created_at) as date'),
                DB::raw('SUM(sale_details.sale_amount) as total')
            ]);

        $dailyCreditSale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.sale_type', '=', 'Credit Sale')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('sale_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(sale_details.created_at)"))
            ->get([
                DB::raw('DATE(sale_details.created_at) as date'),
                DB::raw('SUM(sale_details.sale_amount) as total')
            ]);

        $dailyGSTSale = SaleTax::join('sale_tax_details', 'sale_tax_details.sale_id', '=', 'sale_taxes.id')
            //->where('sales.sale_type', '=', 'Credit Sale')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('sale_tax_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(sale_tax_details.created_at)"))
            ->get([
                DB::raw('DATE(sale_tax_details.created_at) as date'),
                DB::raw('SUM(sale_tax_details.total) as total')
            ]);

        $dailyCashReceipt = GeneralVoucher::where('v_type', '=', 'Cash Receipt')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('general_vouchers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(general_vouchers.created_at)"))
            ->get([
                DB::raw('DATE(general_vouchers.created_at) as date'),
                //DB::raw('SUM(general_vouchers.debit) as debit'),
                DB::raw('SUM(general_vouchers.credit) as credit')
            ]);

        $dailybankReceipt = GeneralVoucher::where('v_type', '=', 'Bank Receipt')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('general_vouchers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(general_vouchers.created_at)"))
            ->get([
                DB::raw('DATE(general_vouchers.created_at) as date'),
                //DB::raw('SUM(general_vouchers.debit) as debit'),
                DB::raw('SUM(general_vouchers.credit) as credit')
            ]);

        $dailyPostDatedReceipt = GeneralVoucher::where('v_type', '=', 'Post Dated Cheque')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('general_vouchers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(general_vouchers.created_at)"))
            ->get([
                DB::raw('DATE(general_vouchers.created_at) as date'),
                //DB::raw('SUM(general_vouchers.debit) as debit'),
                DB::raw('SUM(general_vouchers.credit) as credit')
            ]);

        $dailyCashPayment = GeneralVoucher::where('v_type', '=', 'Cash Payment')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('general_vouchers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(general_vouchers.created_at)"))
            ->get([
                DB::raw('DATE(general_vouchers.created_at) as date'),
                //DB::raw('SUM(general_vouchers.debit) as debit'),
                DB::raw('SUM(general_vouchers.debit) as debit')
            ]);


        $dailyBankPayment = GeneralVoucher::where('v_type', '=', 'Bank Payment')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('general_vouchers.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("day(general_vouchers.created_at)"))
            ->get([
                DB::raw('DATE(general_vouchers.created_at) as date'),
                //DB::raw('SUM(general_vouchers.debit) as debit'),
                DB::raw('SUM(general_vouchers.debit) as debit')
            ]);
        //return $dailybankReceipt;


        $dailyCashSaleRecords = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.sale_type', '=', 'Cash Sale')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('sale_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("date(sale_details.created_at)"))
            ->get([
                DB::raw('DATE(sale_details.created_at) as date'),
                DB::raw('SUM(sale_details.sale_amount) as total')
            ]);

        $dailyCreditSaleRecords = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            ->where('sales.sale_type', '=', 'Credit Sale')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('sale_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("date(sale_details.created_at)"))
            ->get([
                DB::raw('DATE(sale_details.created_at) as date'),
                DB::raw('SUM(sale_details.sale_amount) as total')
            ]);

        // return $dailyCashSaleRecords;

        $dailyCreditCashSaleRecords = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
            // ->where('sales.sale_type', '=', 'Credit Sale')
            //->whereBetween('sale_details.created_at', [$start,$end])
            ->whereBetween('sale_details.created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->groupBy(DB::raw("date(sale_details.created_at)"))
            ->get([
                DB::raw('DATE(sale_details.created_at) as date'),
                DB::raw('SUM(sale_details.sale_amount) as total')
            ]);

        //return $dailyCreditCashSaleRecords;

        $groupBySalesMonth = Sales::with('sale_details')
            ->groupBy(DB::raw("month(sales.date)"))
            ->get([DB::raw('MONTHNAME(sales.date) as month')]);

        // return $groupBySalesMonth;

        $totalMonthlyCashSale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
        // ->groupBy(DB::raw("month(sales.date)"))
        ->OrderBy('sales.date', 'asc')
        ->where('sales.sale_type', '=', 'Cash Sale')
        ->get();

        $totalMonthlyCreditSale = Sales::join('sale_details', 'sale_details.sale_id', '=', 'sales.id')
        // ->groupBy(DB::raw("month(sales.date)"))
        ->OrderBy('sales.date', 'asc')
        ->where('sales.sale_type', '=', 'Credit Sale')
        ->get();

        // return $totalMonthlyCashSale;

        // ->groupBy(DB::raw("month(sales.date)"))
        //     ->OrderBy('sales.date', 'asc')
        //     //->where('sales.sale_type', '=', 'Credit Sale')
        //     ->get([
        //         DB::raw('YEAR(sales.date) as year'),
        //         DB::raw('MONTHNAME(sales.date) as month'),
        //         DB::raw('SUM(sale_details.sale_amount) as total'),
        //         'sales.sale_type as type'
        //     ]);

        return view(
            'dashboard.index',
            compact(
                'party',
                'supplier',
                'product',
                'user',
                'purchases',
                'sales',
                'Gstsales',
                'expenses',
                'todaySale',
                'totalSale',
                'totalPurchase',
                'monthlyCashSale',
                'GSTSales',
                'monthlyCreditSale',
                'banks',
                'dailyCashSale',
                'dailyCreditSale',
                'dailyGSTSale',
                'dailyCashReceipt',
                'dailybankReceipt',
                'dailyPostDatedReceipt',
                'dailyCashPayment',
                'dailyBankPayment',
                'company_detail',
                'dailyCashSaleRecords',
                'dailyCreditSaleRecords',
                'dailyCreditCashSaleRecords',
                'groupBySalesMonth',
                'totalMonthlyCashSale',
                'totalMonthlyCreditSale'
            )
        );
    }


    public function calender()
    {
        return view('calender.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
