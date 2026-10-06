<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SaleDetail;
use App\Models\PurchaseDetail;
use DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chartjs()
    {
    }

    public function index()
    {
        $purchase = PurchaseDetail::select(DB::raw("SUM(total_cost) as count"))
            ->orderBy("created_at")
            ->groupBy(DB::raw("year(created_at)"))
            ->get()->toArray();
        $purchase = array_column($purchase, 'count');

        $sale = SaleDetail::select(DB::raw("SUM(sale_amount) as count"))
            ->orderBy("created_at")
            ->groupBy(DB::raw("year(created_at)"))
            ->get()->toArray();
        $sale = array_column($sale, 'count');

        return view('reports.index')
            ->with('purchase', json_encode($purchase, JSON_NUMERIC_CHECK))
            ->with('sale', json_encode($sale, JSON_NUMERIC_CHECK));
    }

    public function month()
    {

        $purchase = PurchaseDetail::select(DB::raw("SUM(total_cost) as count"))
            ->orderBy("created_at")
            ->groupBy(DB::raw("month(created_at)"))
            ->get()->toArray();
        $purchase = array_column($purchase, 'count');
        //return $purchase;
        $sale = SaleDetail::select(DB::raw("SUM(sale_amount) as count"))
            ->orderBy("created_at")
            ->groupBy(DB::raw("month(created_at)"))
            ->get()->toArray();
        //$sales = DB::table('sale_details')->select('created_at')->groupBy('created_at')->get();
        //return $sales;
        $sale = array_column($sale, 'count');

        //return $sale;
        return view('reports.month')
            ->with('purchase', json_encode($purchase, JSON_NUMERIC_CHECK))
            ->with('sale', json_encode($sale, JSON_NUMERIC_CHECK));
        //return view('reports.month');
    }

    public function days()
    {
        $purchase = PurchaseDetail::select(DB::raw("SUM(total_cost) as count"))
            ->orderBy("created_at")
            ->groupBy(DB::raw("day(created_at)"))
            ->get()->toArray();
        $purchase = array_column($purchase, 'count');
        //return $purchase;
        $sale = SaleDetail::select(DB::raw("SUM(sale_amount) as count"))
            ->orderBy("created_at")
            ->groupBy(DB::raw("day(created_at)"))
            ->get()->toArray();
        $sale = array_column($sale, 'count');
        //return $sale;
        return view('reports.day')
            ->with('purchase', json_encode($purchase, JSON_NUMERIC_CHECK))
            ->with('sale', json_encode($sale, JSON_NUMERIC_CHECK));
        //return view('reports.month');
    }

    public function expensemonth()
    {
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
