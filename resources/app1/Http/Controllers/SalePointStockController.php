<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\SalePointStock;
use App\Models\Setting;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalePointStockController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    
    public function index()
    {
        $warehouse = Warehouse::OrderBy('name', 'asc')
            ->where('id', '!=', 1)
            ->pluck('name', 'id')->prepend('Select Warehouse', '')->toArray();
        $products = Product::OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')->prepend('Select Product', '')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('salepoint-stock.index', Compact('encrypted_token', 'warehouse', 'products'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $WarehouseID = $request->get('warehouse_id');
        $ProductID = $request->get('product_id');
        $StockReport = $request->get('stock_report');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $warehouse = Warehouse::where('id', '=', $WarehouseID)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        $product = Product::where('id', '=', $ProductID)->get();

        if ($StockReport == 1) {
            $SalePoint = SalePointStock::join('products', 'products.id', 'salepoint_stocks.product_id')
                ->select(DB::raw('product_code, product_name, product_cost, sum(cost_amount) as cost, sum(stockin) as stockin ,sum(stockout) as stockout'))
                ->groupBy('product_id')
                ->where('warehouse_id', '=', $WarehouseID)
                ->where('catagory_id', '!=', 5)
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->get();

            return view('warehouse-stock-report.salepoint-stock', Compact('SalePoint', 'company_detail', 'warehouse', 'fromDate', 'toDate'));
        }

        if ($StockReport == 2) {
            $items = SalePointStock::with('uoms')->where('warehouse_id', '=', $WarehouseID)
                ->where('product_id', '=', $ProductID)
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                //->where('catagory_id', '!=', 5)
                ->get();

            $openingStock = SalePointStock::with('uoms')->where('warehouse_id', '=', $WarehouseID)
                ->where('product_id', '=', $ProductID)
                ->whereDate('date', '>=', '2019-01-01')
                ->whereDate('date', '<', $fromDate)
                ->select(DB::raw('SUM(stockin) as stockin, SUM(stockout) as stockout'))
                ->get();
            //return $openingStock;
            return view('warehouse-stock-report.itemwise', Compact('items', 'company_detail', 'warehouse', 'fromDate', 'toDate', 'product', 'openingStock'));
        }

        // return view('warehouse-stock-report.report', Compact('products', 'company_detail', 'warehouse'));
    }

    public function show($id)
    {

        $SalePoint = SalePointStock::join('products', 'products.id', 'salepoint_stocks.product_id')
            ->select(DB::raw('product_code, product_name,sum(cost_amount), sum(stockin) as stockin ,sum(stockout) as stockout'))
            ->groupBy('product_id')
            ->where('products.catagory_id', '!=', 5)
            ->where('warehouse_id', '=', Auth::User()->shop_id)
            ->get();
        // return $SalePoint;
        $warehouse = Warehouse::where('id', '=', Auth::User()->shop_id)->get();
        //return $product;
        $company_detail = Setting::where('id', '=', 1)->get();

        return view('warehouse-stock-report.salepoint-stock', Compact('SalePoint', 'company_detail', 'warehouse'));
    }
}
