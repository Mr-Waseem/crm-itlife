<?php

namespace App\Http\Controllers;

use App\Models\ProductMapping;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
class ProductMappingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return "ddd";
         $warehouses = Warehouse::with('products_mapping')->OrderBy('id')->get();
        $warehousesmap = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('No Product Map', '0');
        return view('products.mapping.index', compact('warehouses', 'warehousesmap'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request;
        ProductMapping::where('id', '!=', 0)->delete();
         $count = (count($request->warehouse_id));
        for($i = 0; $i < $count; $i++){
               $godownstockDetails=new ProductMapping();
               $godownstockDetails->warehouse_id = $request->warehouse_id[$i];
               $godownstockDetails->product_warehouse_id = $request->product_warehouse_id[$i];
               $godownstockDetails->created_by = Auth::User()->id;
               $godownstockDetails->save();
        }
        return redirect()->back()->with('flash_message', 'Product Mapping Updated Successfully!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ProductMapping  $productMapping
     * @return \Illuminate\Http\Response
     */
    public function show(ProductMapping $productMapping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ProductMapping  $productMapping
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductMapping $productMapping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ProductMapping  $productMapping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductMapping $productMapping)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ProductMapping  $productMapping
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductMapping $productMapping)
    {
        //
    }
}
