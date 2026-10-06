<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Catagory;
use App\Models\Departments;
use App\Models\UOM;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use App\Imports\ProductImport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Excel;
use PDF;
class ProductsReportController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PURCHASE ORDER')
        ->where('right_name', 'EDIT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
       
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PRODUCTS REPORT')
        ->where('right_name', 'ADD')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }

        $code = Product::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = (int)$code->product_code + 1;
        }

        $catagories = Catagory::OrderBy('catagory_name', 'asc')->pluck('catagory_name', 'id')->prepend('Select Product Group', '');
        $uoms = UOM::OrderBy('id', 'asc')->pluck('uom', 'uom')->prepend('Select Unit', '');
        $warehouses = Warehouse::orderBy('id')->pluck('name', 'id')->prepend('Select Godown', '');
        $warehouses = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->prepend('Select Godown', '');
        $pack_type = array('' => 'SELECT PACKTYPE', 'BAG' => 'BAG', 'DRUM' => 'DRUM', 'CANS' => 'CANS', 'BOTTLES' => 'BOTTLES', 'CARTON' => 'CARTON','ROLL' => 'ROLL', 'PCS' => 'PCS', 'DOZEN' => 'DOZEN', 'TANKER' => 'TANKER', 'PACKET' => 'PACKET');
        $product_type = array('' => 'Select Product Type', 'Normal' => 'Normal Product', 'Finish' => 'Finish Product');
        $dept=Departments::pluck('name','id')->prepend('Select Department','');

        return view('products.report.index', compact('catagories', 'uoms', 'warehouses', 'pack_type', 'product_type','dept'));
    }


    public function PrintProducts(Request $request){
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PRODUCTS REPORT')
        ->where('right_name', 'EDIT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        // return "hello";
        $Warehouse = $request->Warehouse_ID;
        if( $Warehouse != 0){
            $allproducts = Catagory::with(['products' => function($query) use ($Warehouse){
                $query->with('warehouse:id,name');
                $query->where('warehouse_id', $Warehouse);
                $query->OrderBy('code', 'asc');
            }])
            
            //  ->where('id', '<', 40)
                ->orderBy('catagory_code', 'asc')
                ->get();
        }else{
            $allproducts = Catagory::with(['products' => function($query) use ($Warehouse){
                $query->with('warehouse:id,name');
                // $query->where('warehouse_id', $Warehouse);
                $query->OrderBy('code', 'asc');
            }])
            
            //  ->where('id', '<', 40)
                ->orderBy('catagory_code', 'asc')
                ->get();
        }
           
            $pdf = PDF::loadView('products.print', compact('allproducts'));
            $fileName =  'products.pdf';
            $pdf->save(base_path('upload/products/' . $fileName));
            return $fileName;

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
