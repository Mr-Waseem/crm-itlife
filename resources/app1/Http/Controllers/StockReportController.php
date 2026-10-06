<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Setting;
use App\Models\Catagory;

class StockReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function AllItems()
    {
        //Stock Report in pcs only
        // $products = Catagory::with('products')(['purchase_detail'])->with('sale_detail')->with('challan_detail')->with('sale_return_detail')->with('purchase_return_detail')->OrderBy('product_name', 'asc')->get();
        // return $products;

        $product = Catagory::with(['products' => function ($query) {
            $query->with('products_detail');
            $query->with('sale_detail');
            $query->with('sale_return_detail');
            $query->with('purchase_return_detail');
            $query->with('production_stockin');
            $query->with('production_stockout');
        }])->OrderBy('id', 'asc')->get();
        //return $product;
        //return $products;
        //Stock Report ON different units
        // $products = Product::with(['products_detail' => function($query){
        //                 $query->with('unit');
        //  }])->with(['sale_detail' => function($query){
        //                  $query->with('unit');
        //  }])->with(['purchase_return_detail' => function($query){
        //                  $query->with('unit');
        //  }])->with(['sale_return_detail' => function($query){
        //                  $query->with('uoms');
        // }])->with('challan_detail')->OrderBy('product_name', 'asc')->get();
        //return $products;
        $company_detail = Setting::where('id', '=', 1)->get();
        //return view('stock-report.stock-in-pcs', Compact('products', 'company_detail'));
        return view('stock-report.stock-in-pcs', Compact('product', 'company_detail'));
    }

    public function StockInPcs()
    {
        $products = Product::with(['grn_detail'])->with('sale_detail')->with('challan_detail')->with('sale_return_detail')->with('purchase_return_detail')->OrderBy('product_name', 'asc')->get();
        //return $products;
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('stock-report.all-items', Compact('products', 'company_detail'));
    }
    public function index()
    {
        //
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
