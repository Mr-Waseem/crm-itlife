<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Product;
use App\Models\CashBook;
use App\Models\Warehouse;
use App\Models\Departments;
use App\Models\StockDetails;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\GeneralVoucher;
use App\Models\LedgerDetailWise;
use App\Models\GodownStock;
use App\Models\GodownStockDetail;
use App\Models\ProductMapping;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use App\Models\StockRegisterSpecificItem;
use Illuminate\Support\Facades\Validator;

class OpeningStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
        // $opening_stock = Stock::wheretype('Opening Stock')->OrderBy('id', 'desc')->first();
         $opening_stock = GodownStock::where('type', 'Opening Stock')->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($opening_stock) {
            $codes = $opening_stock->voucher_no + 1;
        }
        //return $codes;
        // return $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`, "_", `product_cost`)
        //  AS `id`, `product_code`, `product_name`, `uom`, `product_cost`'))
        //     ->OrderBy('id', 'asc')
        //     ->pluck('product_name', 'id')
        //     ->prepend('Select Product', '');
        //  $products = Product::
        // select(
        //     DB::raw("CONCAT(id, '_', code,'_', product_name, '_', uom, '_', product_cost) AS product_name")
        //     )
        //     ->
        //     pluck('product_name', 'product_name')
        //     ->prepend('Select Product', '');

            $products = DB::table('products')
            // ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
            ->select(DB::raw("id,CONCAT(id, '_', code, '_', product_name, '_', uom, '_', product_cost, '_', packing) AS  voucher_no"), 
            DB::raw("id, CONCAT(code, '-', product_name) AS  product_name")) 
            // Concatenating the columns
            ->orderBy('code', 'asc')
            ->pluck('product_name', 'voucher_no')
            ->prepend('Select Product', '');

          $warehouseproducts = DB::table('products')
            // ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
            ->select(DB::raw("id,CONCAT(id, '_', code, '_', product_name, '_', uom, '_', product_cost, '_', packing) AS  voucher_no"), 
            DB::raw("id, CONCAT(code, '-', product_name) AS  product_name")) 
            // Concatenating the columns
            ->where('warehouse_id', Auth::user()->warehouse_id)
            ->orderBy('code', 'asc')
            ->pluck('product_name', 'voucher_no')
            ->prepend('Select Product', '');
            // return Auth::user()->role;
        $warehouses = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');
        $singlewarehouses = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->prepend('Select Godown', '');
        $departments = Departments::pluck('name', 'id')->prepend('Select Department', '');
        return view('opening-stock.index', compact('codes', 'products', 'warehouses', 'departments', 'warehouseproducts', 'singlewarehouses'));
    }

    public function LoadProducts(Request $request){
        //  return $request->WarehouseID;
         $mapping = ProductMapping::where('warehouse_id', $request->WarehouseID)->first('product_warehouse_id');
         if(isset($mapping)){
            // return "1";
                $mapproduct = $mapping->product_warehouse_id;
                if($mapproduct > 0){
                    return $warehouseproducts = Product::where('warehouse_id', $mapproduct)
                    ->orderBy('code', 'asc')
                    ->get();
                }
                else{
                    return $warehouseproducts = Product::where('warehouse_id', $request->WarehouseID)
                    ->orderBy('code', 'asc')
                    ->get();
                }
        }else{
            // return "2";
            return $warehouseproducts = Product::where('warehouse_id', $request->WarehouseID)
            ->orderBy('code', 'asc')
            ->get();
        }

    }

   

    public function store(Request $request)
    {
        // return "ddd";
        //   return $request;
        //   return $request->update_voucher_id;
        //   return count($request->price);
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
            'warehouse_id' => 'required'
        ], [
            'date.required' => 'The Voucher Date field is required.',
            'voucher_no.required' => 'The Voucher No field is required.',
            'warehouse_id.required' => 'The Godown field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }
        if (!isset($request->voucher_no)) {
            return redirect()->back()->with('failure_message', 'Please Enter Voucher No');
        }
        if (!isset($request->product_id)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if (!isset($request->price)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'OPENING STOCK')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //  return "udpate";
            // return $request;
           $godownstock = GodownStock::find($request->update_voucher_id);
            $data = $request->all();
            $data['dcn_no'] = $request->dcn_no1;
            $data['updated_by'] = Auth::User()->id;
            $data['to_warehouse_id'] = $request->warehouse_id;
            $data['type'] = "OPENING STOCK";
            $data['party_id'] = 0;
            $godownstock->update($data);
            GodownStockDetail::whereType('OPENING STOCK')
            ->where('transaction_id', $godownstock->id)->delete();
        //    return "deleted";
            // GeneralVoucher::where('sale_id', $stock->id)->delete();
            // LedgerDetailWise::where('sale_id', $stock->id)->delete();
            // StockRegisterSpecificItem::where('sale_id', $stock->id)->delete();
            // CashBook::where('sale_id', $stock->id)->delete();
            $sum = 0;
            $totalQty = 0;
            $totalAmount = 0;
            $totalSaleRate = 0;
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
               $godownstockDetails=new GodownStockDetail();
               $godownstockDetails->voucher_no = $godownstock->voucher_no;
               $godownstockDetails->transaction_id = $godownstock->id;
            //    $godownstockDetails->inward_gatepass_id = $godownstock->inward_gatepass_id;
               $godownstockDetails->date= $godownstock->date;
               $godownstockDetails->type = 'OPENING STOCK';
               $godownstockDetails->warehouse_id = $request->warehouse_id;
               $godownstockDetails->party_id = $godownstock->party_id;
               $godownstockDetails->product_id = $request->product_id[$i];
               $godownstockDetails->qty_in = $request->qty[$i];
               $godownstockDetails->qty_out =0;
            //    $godownstockDetails->demand_qty =$request->demandQty[$i];
               $godownstockDetails->remarks = $godownstock->remarks;
               $godownstockDetails->sale_rate = $request->price[$i];
               //   $godownstockDetails->amount = $request->total[$i];
               $godownstockDetails->amount = $request->qty[$i]*$request->price[$i];
               $godownstockDetails->created_by = $godownstock->created_by;
               $godownstockDetails->updated_by = Auth::User()->id;
               $godownstockDetails->save();
            }
            return redirect()->back()->with('flash_message', 'Opening Stock Voucher Updated Successfully!');
        } else {
            // return $request;
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'OPENING STOCK')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            
            $VoucherNo = GodownStock::where('type', 'Opening Stock')->OrderBy('id', 'desc')->first();
           $codes = 1;
           if ($VoucherNo) {
            $codes = $VoucherNo->voucher_no + 1;
            }
        //    return $codes;
           $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            $data['to_warehouse_id'] = $request->warehouse_id;
            $data['voucher_no'] = $codes;
            $data['type'] = "OPENING STOCK";
            $data['party_id'] = 0;
            $godownstock = GodownStock::create($data);
            $sum = 0;
            $totalQty = 0;
            $totalAmount = 0;
            $totalSaleRate = 0;
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $godownstockDetails=new GodownStockDetail();
               $godownstockDetails->voucher_no = $godownstock->voucher_no;
               $godownstockDetails->transaction_id = $godownstock->id;
            //    $godownstockDetails->inward_gatepass_id = $godownstock->inward_gatepass_id;
               $godownstockDetails->date= $godownstock->date;
               $godownstockDetails->type = 'OPENING STOCK';
               $godownstockDetails->warehouse_id = $request->warehouse_id;
               $godownstockDetails->party_id = $godownstock->party_id;
               $godownstockDetails->product_id = $request->product_id[$i];
               $godownstockDetails->qty_in = $request->qty[$i];
               $godownstockDetails->qty_out =0;
            //    $godownstockDetails->demand_qty =$request->demandQty[$i];
               $godownstockDetails->remarks = $godownstock->remarks;
               $godownstockDetails->sale_rate = $request->price[$i];
               $godownstockDetails->amount = $request->total[$i];
               $godownstockDetails->created_by = Auth::User()->id;
               $godownstockDetails->save();
            }
            return redirect()->back()->with('flash_message', 'Opening Stock Voucher Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
       $edit = GodownStock::whereType('OPENING STOCK')
       ->where('voucher_no', $request->voucher_no)
       ->first();
        if ($edit) {
            $data = GodownStockDetail::with('product')
                ->with(['godownstock' => function ($query) {
                    $query->with('warehouse:id,name');
                }])
                // ->whereType('OPENING STOCK')
                // ->where('voucher_no', $edit->voucher_no)

                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('type', "OPENING STOCK")
                // ->where('voucher_no', $sale)
                ->where('transaction_id', $edit->id)
                // ->orderby('product_id')
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        // $sale = Stock::whereType('Opening Stock')->where('voucher_no', '<', $request->voucher_no)->max('voucher_no');
      $sale = GodownStock::whereType('OPENING STOCK')
      ->where('voucher_no', '<', $request->voucher_no)
      ->max('id');

        if ($sale) {
            $data = GodownStockDetail::with(['product'])
                ->with(['godownstock' => function ($query) {
                    $query->with('warehouse:id,name');
                }])
                // ->whereType('OPENING STOCK')
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('type', "OPENING STOCK")
                // ->where('voucher_no', $sale)
                ->where('transaction_id', $sale)
                // ->orderby('product_id')
                ->get();
   
            return Response::json(['data' => $data]);
            // return Response::json(['data' => $data, 'warehouseproducts' => $warehouseproducts]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $sale = GodownStock::whereType('OPENING STOCK')
        ->where('voucher_no', '>', $request->voucher_no)
        ->min('id');
        // $sale = GodownStock::whereType('OPENING STOCK')->where('voucher_no', '<', $request->voucher_no)->max('voucher_no');

        if ($sale) {
            $data = GodownStockDetail::with('product')
                ->with(['godownstock' => function ($query) {
                    $query->with('warehouse:id,name');
                }])
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('type', "OPENING STOCK")
                // ->where('voucher_no', $sale)
                ->where('transaction_id', $sale)
                // ->orderby('product_id')
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        // return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'OPENING STOCK')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return $request;
        $stock = GodownStock::whereType('OPENING STOCK')
        ->where('to_warehouse_id', Auth::User()->warehouse_id)
        ->where('voucher_no', $request->delete_voucher_no)
        ->first();
        if ($stock) {
            GodownStock::findOrFail($stock->id)->delete();
            GodownStockDetail::whereType('OPENING STOCK')
            ->where('transaction_id', $stock->id)->delete();
            // GeneralVoucher::where('sale_id', $stock->id)->delete();
            // LedgerDetailWise::where('sale_id', $stock->id)->delete();
            // StockRegisterSpecificItem::where('sale_id', $stock->id)->delete();
            // CashBook::where('sale_id', $stock->id)->delete();
            return redirect()->back()->with('flash_message', 'Opening Stock Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() .'/upload/opening-stock');
        $voucher_no = $request->voucher_no;
        $stock = GodownStock::where('type','OPENING STOCK')->where('voucher_no', $voucher_no)->first();
        if ($stock) {
        //    $stockdetail = GodownStockDetail::with(['godownstock'=>function($qry){
        //                     $qry->with('warehouse:id,name');
        //                     $qry->with('generated_by:id,name');
                            
        //     }])
                
        //         ->with('product:id,product_name,uom,code,packing')
        //         ->where('voucher_no', $voucher_no)
        //         ->where('type','OPENING STOCK')
        //         ->orderBy('id', 'asc')
        //         ->get();

          $stockdetail = GodownStock::with(['godown_stock_details' => function($que){
            $que->with('product:id,product_name,uom,code,packing');
        }])
        ->with('warehouse:id,name')
        ->with('generated_by:id,name')
        ->where('voucher_no', $voucher_no)
                ->where('type','OPENING STOCK')
                ->orderBy('id', 'asc')
                ->get();
            $pdf = PDF::loadView('opening-stock.invoice', compact('stockdetail'));
            $fileName =  'opening-stock' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/opening-stock/' . $fileName));
            return $fileName;
        } 
    }
}