<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Catagory;
use App\Models\Product;
use App\Models\Setting;

class StockCatagoryWiseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $catagories = Catagory::OrderBy('catagory_name', 'asc')->pluck('catagory_name', 'id')->prepend('Select Catagory', '')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('stock-register-catagory-wise.index', Compact('encrypted_token', 'catagories'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'catagory_id' => 'required' //,
            // 'from_date' => 'required',
            // 'to_date' => 'required'
        ]);
        $CatagoryID = $request->get('catagory_id');
        // $fromDate = $request->get('from_date');
        // $toDate = $request->get('to_date');
        $catagory = Catagory::where('id', '=', $CatagoryID)->get();
        //return $catagory;
        // $items = StockRegisterSpecificItem::join('products', 'products.id', '=', 'stock_register_specific_items.product_id')
        //  ->join('parties', 'parties.id', '=', 'stock_register_specific_items.party_id')
        //  ->OrderBy('stock_register_specific_items.id', 'asc')
        //  ->whereBetween('stock_register_specific_items.date', [$fromDate, $toDate])
        //  ->where('stock_register_specific_items.product_id', '=', $ProductID)->get();

        // $product = Catagory::with(['products' => function($query){
        //                      $query->with('products_detail');
        //                      $query->with('sale_detail');
        //                      $query->with('sale_return_detail');
        //                      $query->with('purchase_return_detail');
        //       }])->OrderBy('catagory_name', 'asc')
        // ->where('products.id',  $CatagoryID)
        // ->get();
        $product = Product::with('products_detail')->with('sale_detail')->with('sale_return_detail')->with('purchase_return_detail')
            ->OrderBy('product_name', 'asc')
            ->where('products.catagory_id',  $CatagoryID)
            ->get();
        //return $product;
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('stock-register-catagory-wise.report', Compact('items', 'company_detail', 'product', 'catagory'));
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
