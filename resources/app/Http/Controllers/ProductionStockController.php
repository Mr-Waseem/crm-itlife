<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionStock;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class ProductionStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('warehouse-stock-report.production-create');
    }

    public function store(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $ProductionStock = ProductionStock::join('products', 'products.id', 'production_stocks.product_id')
            ->select(DB::raw('product_code, product_name, product_cost, sum(cost_amount), sum(stockin) as stockin ,sum(stockout) as stockout'))
            ->groupBy('product_id')
            ->whereDate('production_stocks.created_at', '>=', $fromDate)
            ->whereDate('production_stocks.created_at', '<=', $toDate)
            ->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('warehouse-stock-report.production-stock', Compact('ProductionStock', 'company_detail', 'fromDate', 'toDate'));
    }
}
