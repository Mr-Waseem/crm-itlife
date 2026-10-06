<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Setting;
use App\Models\PurchaseDetail;
use App\Models\SaleDetail;
use DB;

class WarehouseStockReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Warehouse', '')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('warehouse-stock-report.index', Compact('encrypted_token', 'warehouse'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $WarehouseID = $request->get('warehouse_id');
        //return $WarehouseID;
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        // $product = Warehouse::with(['products' => function($query){
        //                        $query->with('products_detail');
        //                        // $query->with('sale_detail');
        //                        // $query->with('sale_return_detail');
        //                        // $query->with('purchase_return_detail');
        //                       // $query->with('production_stockin');
        //                       // $query->with('production_stockout');
        //        }])
        // //->whereBetween('date', [$fromDate, $toDate])
        // ->where('id', '=', $WarehouseID)
        // ->get();


        // $product = Warehouse::with(['products_detail' => function ($query){
        //    $query->with('products');
        // }])
        // ->where('id', '=', $WarehouseID)
        // ->get();


        //  $totalRevenue = PurchaseDetail::with([
        // 'products' => function($query) {$query->select('id', 'product_id');}])
        // // 'poc' => function($query) {$query->select('id', 'poc_id');}, 
        // // 'user' => function($query) {$query->select('id', 'id');}])

        //    ->groupBy('warehouse_id')
        //    ->where('warehouse_id', '=', 1)
        //     //->sum('quantity')
        //     ->sum('total_cost');

        $warehouse = Warehouse::where('id', '=', $WarehouseID)->get();
        //return $warehouse[0]->id;
        $product = PurchaseDetail::join('products', 'products.id', 'purchase_details.product_id')
            ->select(DB::raw('product_code, product_name,quantity,sum(unit_cost) as cost , sum(total_cost) as total_cost ,sum(quantity) as stock'))
            ->groupBy('product_id')
            ->where('warehouse_id', '=', $warehouse[0]->id)
            ->get();

        $sales = SaleDetail::join('products', 'products.id', 'sale_details.product_id')
            ->select(DB::raw('product_code, product_name,quantity,sum(sale_rate) as rate , sum(sale_amount) as amount ,sum(quantity) as stock'))
            ->groupBy('product_id')
            ->where('warehouse_id', '=', $warehouse[0]->id)
            ->get();

        //return $product;

        $company_detail = Setting::where('id', '=', 1)->get();
        return view('warehouse-stock-report.warehouse-in-pcs', Compact('product', 'sales', 'company_detail', 'warehouse'));
        // return view('warehouse-stock-report.report', Compact('products', 'company_detail', 'warehouse'));
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
