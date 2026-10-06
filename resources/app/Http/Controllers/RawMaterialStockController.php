<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RawMaterialStock;
use App\Models\Setting;
use Illuminate\Support\Facades\DB;

class RawMaterialStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('warehouse-stock-report.raw-create');
    }

    public function store(Request $request)
    {
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $RawMaterial = RawMaterialStock::join('products', 'products.id', 'raw_material_stocks.product_id')
            ->select(DB::raw('product_code, product_name, product_cost, sum(cost_amount) as CostAmount, sum(stockin) as stockin ,sum(stockout) as stockout'))
            ->groupBy('product_id')
            ->whereDate('date', '>=', $fromDate)
            ->whereDate('date', '<=', $toDate)
            ->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('warehouse-stock-report.raw-material', Compact('RawMaterial', 'company_detail', 'fromDate', 'toDate'));
    }
}
