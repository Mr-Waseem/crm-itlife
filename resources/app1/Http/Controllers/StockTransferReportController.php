<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Catagory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockTransferReportController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function create()
    {
        $warehouse = Warehouse::OrderBy('name', 'asc')
            ->where('id', '!=', 1)
            ->pluck('name', 'id')->prepend('Select Warehouse', '')->toArray();

        $userShop = Warehouse::where('id', '=', Auth::User()->shop_id)->get();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('stock-transfer.stock-transfer-report.create', Compact('encrypted_token', 'warehouse', 'userShop'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $WarehouseID = $request->get('warehouse_id');
        $ReportDetail = $request->get('ReportDetail');

        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $warehouse = Warehouse::where('id', '=', $WarehouseID)->get();

        $company_detail = Setting::where('id', '=', 1)->get();

        if ($ReportDetail == 1) {
            $catagories = Catagory::OrderBy('id', 'asc')->get();
            $product = Catagory::join('products', 'products.catagory_id', 'catagories.id')
                ->join('salepoint_stocks', 'salepoint_stocks.product_id', 'products.id')
                ->select('catagories.id', 'catagories.catagory_name', 'products.product_code', 'products.product_name', DB::raw('SUM(salepoint_stocks.stockin) as Stockin'), DB::raw('SUM(salepoint_stocks.stockout) as Stockout'), DB::raw('SUM(salepoint_stocks.cost_amount) as amount'))
                ->whereDate('salepoint_stocks.created_at', '>=', $fromDate)
                ->whereDate('salepoint_stocks.created_at', '<=', $toDate)
                ->where('warehouse_id', '=', $WarehouseID)
                ->where('products.catagory_id', '!=', 5)
                ->groupBy('salepoint_stocks.product_id')
                ->OrderBy('products.product_name', 'asc')
                ->get();

            return view('stock-transfer.stock-transfer-report.summary', Compact('product', 'company_detail', 'warehouse', 'fromDate', 'toDate', 'catagories'));
        }

        if ($ReportDetail == 2) {
            $SalePoint = Product::join('salepoint_stocks', 'salepoint_stocks.product_id', 'products.id')
                ->whereDate('salepoint_stocks.created_at', '>=', $fromDate)
                ->whereDate('salepoint_stocks.created_at', '<=', $toDate)
                ->where('warehouse_id', '=', $WarehouseID)
                ->where('products.catagory_id', '!=', 5)
                ->get();

            return view('stock-transfer.stock-transfer-report.index', Compact('SalePoint', 'company_detail', 'warehouse', 'encrypted_token', 'fromDate', 'toDate'));
        }
    }
}
