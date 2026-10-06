<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\GodownStock;
use Illuminate\Http\Request;
use App\Models\GodownStockDetail;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
class StockTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return "D";
        //  $all = GodownStock::with('godown_stock_details')->where('type','STOCK TRANSFER')
        // ->where('from_warehouse_id',Auth::User()->warehouse_id)->get();
        // // return count($all);
        // foreach($all as $data1){
        //     foreach($data1->godown_stock_details as $data){
        //         // return $data;
        //         $data->delete();
        //     }
        //     // return $data1;
        //      $data1->delete();
        // }
        // return "done";

         $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
        ->where('type','STOCK TRANSFER')->max('voucher_no');
        $codes = 1;
        if ($code) {
            $codes = $code + 1;
        }
        // return $codes;
        //  $products = Product::where('warehouse_id', Auth::User()->warehouse_id)
        //     // ->where('product_type','Finish')
        //     ->OrderBy('id', 'asc')
        //     ->pluck('product_name', 'id');

          $products = Product::join('godown_stock_details', 'godown_stock_details.product_id', 'products.id')
            // ->select('products.product_name')
            ->select('products.id', 'products.product_name', 'products.code', DB::raw('SUM(godown_stock_details.qty_in) as InQty'), DB::raw('SUM(godown_stock_details.qty_out) as OutQty'))
            ->groupby('godown_stock_details.product_id')
             ->where('godown_stock_details.warehouse_id', Auth::User()->warehouse_id)
             // ->where('product_type','Finish')
             //->OrderBy('id', 'asc')
             //->pluck('product_name', 'id');
             ->get();
            
             $StockProduct = [];
             foreach($products as $product){
                $qty = $product->InQty - $product->OutQty;
                if($qty > 0){
                    $StockProduct[] = $product;
                }
             }
            //  return $StockProduct;
             
               $products = Product::
            //    where('warehouse_id', Auth::User()->warehouse_id)->
              where('product_type','Finish')
             ->OrderBy('id', 'asc')
             ->pluck('product_name', 'id');
             //return $StockProduct;
           

    //    return $StockDetailProduct=Product::join('godown_stock_details','godown_stock_details.product_id','=','products.id')
    //         ->where('godown_stock_details.warehouse_id',Auth::User()->warehouse_id)
    //          ->where('godown_stock_details.type','STOCK TRANSFER')
    //          ->where('godown_stock_details.qty_out','!=',0)
    //          ->distinct('godown_stock_details.product_id')
    //          ->pluck('products.product_name', 'products.id');
           
        $warehouseFrom = Warehouse::select('name')->where('id', Auth::User()->warehouse_id)->first();
        $warehouseTo = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');

        return view('stock-transfer.index', compact('products', 'codes', 'warehouseFrom', 'warehouseTo', 'StockProduct'));
    }

    public function storebk(Request $request)
    {
        
        $Validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'voucher_no' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator)->withInput();
        }
        if (!isset($request->price)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if (!isset($request->qty)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        
        if ($request->update_voucher_id != null || $request->update_voucher_id != 0) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'STOCK TRANSFER')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return $request;

         $added = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
        ->where('type','STOCK TRANSFER')->where('voucher_no', $request->voucher_no)->first();
        if($added){
            // return "dd";
             $stockTransfer = GodownStock::where('id', $request->update_voucher_id)
            ->where('from_warehouse_id', Auth::User()->warehouse_id)
            ->where('type','STOCK TRANSFER')->first();
            $stockTransfer = GodownStock::where('id', $stockTransfer->id)->first();
            $data = $request->all();
            $data['voucher_no'] = $stockTransfer->voucher_no;
            $stockTransfer->update($data);
            //  return $request;
        //    return "near";
             GodownStockDetail::where('transaction_id', $request->update_voucher_id)
            ->where('type','STOCK TRANSFER')->delete();
            $count = count($request->product_id);
            $totalQty = 0;
            $totalAmount = 0;
            for ($i = 0; $i < $count; $i++) {
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                // $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->qty_in =0;
                $stockTransferDetails->qty_out = $request->qty[$i];
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = $stockTransfer->created_by;
                $stockTransferDetails->updated_by = Auth::User()->id;
                $stockTransferDetails->save();

                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $request->qty[$i];
                $stockTransferDetails->qty_out =0;
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = $stockTransfer->created_by;
                $stockTransferDetails->updated_by = Auth::User()->id;
                $stockTransferDetails->save();

            }
          
            $stockTransfer->created_by = $stockTransfer->created_by;
            $stockTransfer->updated_by = Auth::User()->id;
            $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Stock Transfer Updated Successfully!');
        }else{
            $added = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
            ->where('type','STOCK TRANSFER')->where('voucher_no', $request->voucher_no)->first();
            if($added){
                $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
                ->where('type','STOCK TRANSFER')->max('voucher_no');
                $codes = 1;
                if ($code) {
                    $codes = $code + 1;
                }
            }else{
                $codes = $request->voucher_no;
            }
            // return $codes;
            $data = $request->all();
            $data['voucher_no'] = $codes;
            $data['status'] =0;
            $data['from_warehouse_id'] = Auth::User()->warehouse_id;
            $stockTransfer = GodownStock::create($data);
    
                $count = count($request->product_id);
                $totalQty = 0;
                $totalAmount = 0;
                for ($i = 0; $i < $count; $i++) {
                    $stockTransferDetails = new GodownStockDetail();
                    $stockTransferDetails->transaction_id = $stockTransfer->id;
                    $stockTransferDetails->date = $stockTransfer->date;
                    $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                    $stockTransferDetails->product_id = $request->product_id[$i];
                    $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                    $stockTransferDetails->qty_out = $request->qty[$i];
                    $stockTransferDetails->qty_in =0;
                    $stockTransferDetails->rate = $request->price[$i];
                    $stockTransferDetails->amount = $request->total[$i];
                    $stockTransferDetails->type = $stockTransfer->type;
                    $stockTransferDetails->created_by = Auth::User()->id;
                    $stockTransferDetails->save();
    
                    $stockTransferDetails = new GodownStockDetail();
                    $stockTransferDetails->transaction_id = $stockTransfer->id;
                    $stockTransferDetails->date = $stockTransfer->date;
                    $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                    $stockTransferDetails->product_id = $request->product_id[$i];
                    $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                    $stockTransferDetails->qty_in = $request->qty[$i];
                    $stockTransferDetails->qty_out =0;
                    $stockTransferDetails->rate = $request->price[$i];
                    $stockTransferDetails->amount = $request->total[$i];
                    $stockTransferDetails->type = $stockTransfer->type;
                    $stockTransferDetails->created_by = Auth::User()->id;
                    $stockTransferDetails->save();
                }
    
                $stockTransfer->created_by = Auth::User()->id;
                $stockTransfer->save();
    
                return redirect()->back()->with('flash_message', 'Stock Transfer Added Successfully!');
        }


       
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'STOCK TRANSFER')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            //  return $request;
        //  $code = GodownStock::where('to_warehouse_id', Auth::User()->warehouse_id)
        //  ->where('type','STOCK TRANSFER')->OrderBy('id', 'desc')->first();
        // $codes = 1;
        // if ($code) {
        //     $codes = $code->voucher_no + 1;
        // }

        $added = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
        ->where('type','STOCK TRANSFER')->where('voucher_no', $request->voucher_no)->first();
        if($added){
            $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
            ->where('type','STOCK TRANSFER')->max('voucher_no');
            $codes = 1;
            if ($code) {
                $codes = $code + 1;
            }
        }else{
            $codes = $request->voucher_no;
        }
        // return $codes;
        $data = $request->all();
        $data['voucher_no'] = $codes;
        $data['status'] =0;
        $data['from_warehouse_id'] = Auth::User()->warehouse_id;
        $stockTransfer = GodownStock::create($data);

            $count = count($request->product_id);
            $totalQty = 0;
            $totalAmount = 0;
            for ($i = 0; $i < $count; $i++) {
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->qty_out = $request->qty[$i];
                $stockTransferDetails->qty_in =0;
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $request->qty[$i];
                $stockTransferDetails->qty_out =0;
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();
            }

            $stockTransfer->created_by = Auth::User()->id;
            $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Stock Transfer Added Successfully!');
        }
        abort(500);
    }


    public function store(Request $request)
    {
        
        $Validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'voucher_no' => 'required',
            'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator)->withInput();
        }
        if (!isset($request->price)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if (!isset($request->qty)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        
        // return $request;
         $added = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
        ->where('type','STOCK TRANSFER')->where('voucher_no', $request->voucher_no)->first();
        if($added){

            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'STOCK TRANSFER')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            // return "dd";
             $stockTransfer = GodownStock::where('id', $request->update_voucher_id)
            ->where('from_warehouse_id', Auth::User()->warehouse_id)
            ->where('type','STOCK TRANSFER')->first();
            $stockTransfer = GodownStock::where('id', $stockTransfer->id)->first();
            $data = $request->all();
            $data['voucher_no'] = $stockTransfer->voucher_no;
            $stockTransfer->update($data);
            //  return $request;
        //    return "near";
             GodownStockDetail::where('transaction_id', $request->update_voucher_id)
            ->where('type','STOCK TRANSFER')->delete();
            $count = count($request->product_id);
            $totalQty = 0;
            $totalAmount = 0;
            for ($i = 0; $i < $count; $i++) {
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                // $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->qty_in =0;
                $stockTransferDetails->qty_out = $request->qty[$i];
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = $stockTransfer->created_by;
                $stockTransferDetails->updated_by = Auth::User()->id;
                $stockTransferDetails->save();

                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $request->qty[$i];
                $stockTransferDetails->qty_out =0;
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = $stockTransfer->created_by;
                $stockTransferDetails->updated_by = Auth::User()->id;
                $stockTransferDetails->save();

            }
          
            $stockTransfer->created_by = $stockTransfer->created_by;
            $stockTransfer->updated_by = Auth::User()->id;
            $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Stock Transfer Updated Successfully!');
        }else{
            $added = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
            ->where('type','STOCK TRANSFER')->where('voucher_no', $request->voucher_no)->first();
            if($added){
                $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
                ->where('type','STOCK TRANSFER')->max('voucher_no');
                $codes = 1;
                if ($code) {
                    $codes = $code + 1;
                }
            }else{
                $codes = $request->voucher_no;
            }
            // return $codes;
            $data = $request->all();
            $data['voucher_no'] = $codes;
            $data['status'] =0;
            $data['from_warehouse_id'] = Auth::User()->warehouse_id;
            $stockTransfer = GodownStock::create($data);
    
                $count = count($request->product_id);
                $totalQty = 0;
                $totalAmount = 0;
                for ($i = 0; $i < $count; $i++) {
                    $stockTransferDetails = new GodownStockDetail();
                    $stockTransferDetails->transaction_id = $stockTransfer->id;
                    $stockTransferDetails->date = $stockTransfer->date;
                    $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                    $stockTransferDetails->product_id = $request->product_id[$i];
                    $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                    $stockTransferDetails->qty_out = $request->qty[$i];
                    $stockTransferDetails->qty_in =0;
                    $stockTransferDetails->rate = $request->price[$i];
                    $stockTransferDetails->amount = $request->total[$i];
                    $stockTransferDetails->type = $stockTransfer->type;
                    $stockTransferDetails->created_by = Auth::User()->id;
                    $stockTransferDetails->save();
    
                    $stockTransferDetails = new GodownStockDetail();
                    $stockTransferDetails->transaction_id = $stockTransfer->id;
                    $stockTransferDetails->date = $stockTransfer->date;
                    $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                    $stockTransferDetails->product_id = $request->product_id[$i];
                    $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                    $stockTransferDetails->qty_in = $request->qty[$i];
                    $stockTransferDetails->qty_out =0;
                    $stockTransferDetails->rate = $request->price[$i];
                    $stockTransferDetails->amount = $request->total[$i];
                    $stockTransferDetails->type = $stockTransfer->type;
                    $stockTransferDetails->created_by = Auth::User()->id;
                    $stockTransferDetails->save();
                }
    
                $stockTransfer->created_by = Auth::User()->id;
                $stockTransfer->save();
    
                return redirect()->back()->with('flash_message', 'Stock Transfer Added Successfully!');
        }


       
        abort(500);
    }

    public function destroy(Request $request)
    {
        //  return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'STOCK TRANSFER')
                ->where('right_name', 'DELETE')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
         $delete = GodownStock::where('voucher_no', $request->delete_invoice_no)->where('type','STOCK TRANSFER')
        ->where('from_warehouse_id',Auth::User()->warehouse_id)->first();
        if($delete){
            GodownStock::where('id', $delete->id)->delete();
            GodownStockDetail::where('transaction_id', $delete->id)
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('type','STOCK TRANSFER')->delete();
            return redirect()->back()->with('flash_message', 'Stock Transfer Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        // return "d";
         $GodownStock = GodownStock::where('voucher_no', $request->voucher_no)
        ->where('type','STOCK TRANSFER')->where('from_warehouse_id',Auth::User()->warehouse_id)->first();
        if ($GodownStock) {
            // $GodownStockDetail = GodownStockDetail::with('product','godownstock:id,remarks,to_warehouse_id')
            // ->where('voucher_no', $request->voucher_no)
            // ->where('type','STOCK TRANSFER')
            // ->where('warehouse_id',Auth::User()->warehouse_id)
            // // ->where('qty_out','!=',null)
            // ->where('qty_out','>', 0)
            // ->get();

            $GodownStockDetail = GodownStock::with(['godown_stock_details' => function($que){
                $que->with('product:id,code,product_name,uom,packing');
                $que->where('type','STOCK TRANSFER');
                $que->where('qty_out','>', 0);
            }])
            ->where('voucher_no', $request->voucher_no)
            ->where('type','STOCK TRANSFER')
            ->where('from_warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_out','>', 0)
            ->get();

            // $GodownStock = GodownStock::where('voucher_no', $request->voucher_no)->first();
            return Response::json(['data' => $GodownStockDetail]);
        } else {
            return Response::json(['data' => '']);
        }
        abort(500);
    }

    public function LoadNextData(Request $request)
    {
        $godownStock = GodownStock::where('voucher_no', '>', $request->voucher_no)->where('type','STOCK TRANSFER')->where('from_warehouse_id',Auth::User()->warehouse_id)->min('voucher_no');
        if ($godownStock) {
            $godownStockDetail = GodownStock::with(['godown_stock_details' => function($que){
                $que->with('product:id,code,product_name,uom,packing');
                $que->where('type','STOCK TRANSFER');
                $que->where('qty_out','>', 0);
            }])
            ->where('voucher_no', $godownStock)
            ->where('type','STOCK TRANSFER')
            ->where('from_warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_out','>', 0)
            ->get();
            return Response::json(['data' => $godownStockDetail]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
         $GodownStock = GodownStock::where('voucher_no', '<', $request->voucher_no)
        ->where('type','STOCK TRANSFER')->where('from_warehouse_id',Auth::User()->warehouse_id)
        ->max('voucher_no');
        if ($GodownStock) {
            //  $GodownStockDetail = GodownStockDetail::with('product','godownstock:id,remarks,to_warehouse_id')
            // ->where('voucher_no', $GodownStock)
            // ->where('type','STOCK TRANSFER')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            // // ->where('qty_out','!=',null)
            // ->where('qty_out','>', 0)
            // ->get();
             $GodownStockDetail = GodownStock::with(['godown_stock_details' => function($que){
                $que->with('product:id,code,product_name,uom,packing');
                $que->where('type','STOCK TRANSFER');
                $que->where('qty_out','>', 0);
            }])
            ->where('voucher_no', $GodownStock)
            ->where('type','STOCK TRANSFER')
            ->where('from_warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_out','>', 0)
            ->get();
            return Response::json(['data' => $GodownStockDetail]);
        } else {
            return Response::json(['data' => '']);
        }
    }
    public function PrintVoucher(Request $request)
    {
    //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //     ->where('voucher_name', 'STOCK TRANSFER')
    //     ->where('right_name', 'PRINT')
    //     ->first();
    // if (!$voucherRight) {
    //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    // }
        File::cleanDirectory(base_path() .'/upload/stock-transfer');
        $voucher_no = $request->voucher_no;
        $GodownStock = GodownStock::where('type','STOCK TRANSFER')->where('from_warehouse_id',Auth::User()->warehouse_id)->where('voucher_no', $voucher_no)->first();
        if ($GodownStock) {
            // $godownStockDetail = GodownStockDetail::with(['godownstock'=>function($qry){
            //                 $qry->with('warehouse_from:id,name','warehouse_to:id,name');
            //                 $qry->where('type','STOCK TRANSFER');
                            
            // }])
            //     ->with('product:id,code,product_name,uom,packing')
            //     ->where('voucher_no', $voucher_no)
            //     ->where('warehouse_id', Auth::User()->warehouse_id)
            //     ->where('type','STOCK TRANSFER')
            //     ->where('qty_out','>', 0)
            //     ->orderBy('id', 'asc')
            //     ->get();

                $godownStockDetail = GodownStock::with(['godown_stock_details' => function($que){
                    $que->with('product:id,code,product_name,uom,packing');
                    $que->where('type','STOCK TRANSFER');
                    $que->where('qty_out','>', 0);
                }])
                ->with('warehouse_from:id,name')->with('warehouse_to:id,name')
                ->where('voucher_no', $voucher_no)
                ->where('type','STOCK TRANSFER')
                ->where('from_warehouse_id',Auth::User()->warehouse_id)
                // ->where('qty_out','>', 0)
                ->get();


                // $GodownStockDetail = GodownStockDetail::with('product','godownstock:id,remarks,to_warehouse_id')
                // ->where('voucher_no', $GodownStock)
                // ->where('type','STOCK TRANSFER')
                // ->where('warehouse_id',Auth::User()->warehouse_id)
                // ->where('qty_out','!=',null)
                // ->get();


            $pdf = PDF::loadView('stock-transfer.invoice', compact('godownStockDetail'));
            $fileName =  'stock-transfer' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/stock-transfer/' . $fileName));
            return $fileName;
        } else{
            return false;  
        }
    }
    public function productRecord(Request $request)
    { 
         $Product = Product::where('id', $request->product_id)->get(['id','code','product_name','uom','product_price','product_cost', 'packing']);
         return json_encode($Product);
    }

    public function report(Request $request)
    {
        // return "0";
        if ($request->ajax()) {
            // return "0";
           $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
            if($product_id==0){
            if ($report_type == "summary") {
                // return "1";
                $stockReportSummaryWise = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
                    ->select(
                        'products.product_name',
                        DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                        DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
                        'godown_stock_details.date'
                    )
                    ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('godown_stock_details.warehouse_id')->get();
                        } else {
                            $query->where('godown_stock_details.warehouse_id', $warehouse_id)->get();
                        }
                    })
                    // ->where('godown_stock_details.product_id', $product_id)
                    ->whereDate('godown_stock_details.date', '>=', $fromDate)
                    ->whereDate('godown_stock_details.date', '<=', $toDate)
                    ->groupBy('godown_stock_details.product_id')
                    ->orderBy('godown_stock_details.product_id')
                    ->get();

                return Response::json($stockReportSummaryWise);
            } else if ($report_type == 'detailed') {
                // return "2";
                $stockReportDetailWise = GodownStockDetail::with('product:id,product_name')
                   
                    ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('warehouse_id')->get();
                        } else {
                            $query->where('warehouse_id', $warehouse_id)->get();
                        }
                        $query->with('warehouse:id,name');
                    })
                    // ->where('product_id', $product_id)
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->orderBy('product_id')
                    ->get();

                return Response::json($stockReportDetailWise);
            }
          }else{
            if ($report_type == "summary") {
                // return "3";
                $stockReportSummaryWise = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
                    ->select(
                        'products.product_name',
                        DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                        DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
                        'godown_stock_details.date'
                    )
                    ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('godown_stock_details.warehouse_id')->get();
                        } else {
                            $query->where('godown_stock_details.warehouse_id', $warehouse_id)->get();
                        }
                    })
                    ->where('godown_stock_details.product_id', $product_id)
                    ->whereDate('godown_stock_details.date', '>=', $fromDate)
                    ->whereDate('godown_stock_details.date', '<=', $toDate)
                    ->groupBy('godown_stock_details.product_id')
                    ->orderBy('godown_stock_details.product_id')
                    ->get();

                return Response::json($stockReportSummaryWise);
            } else if ($report_type == 'detailed') {
                // return "4";
                $stockReportDetailWise = GodownStockDetail::with('product:id,product_name')
                   
                    ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('warehouse_id')->get();
                        } else {
                            $query->where('warehouse_id', $warehouse_id)->get();
                        }
                        $query->with('warehouse:id,name');
                    })
                    ->where('product_id', $product_id)
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->orderBy('product_id')
                    ->get();

                return Response::json($stockReportDetailWise);
            }
          }
        }
        //  return "all";

        $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('All Product', 0);
         $warehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->orderBy('name')->pluck('name', 'id')->prepend('All', 0);
        //   return $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
        return view('inventory-reports.index', compact('products', 'warehouse'));
    }
    
    
    public function StockReport(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'STOCK TRANSFER REPORT')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $warehouse = Warehouse::find($warehouse_id);

            if ($product_id == 0) {
                $stockReportSummary = StockTransferDetails::join('products', 'products.id', '=', 'stock_transfer_details.product_id')
                    ->join('warehouses', 'warehouses.id', '=', 'stock_transfer_details.warehouse_id')
                    ->select(
                        'stock_transfer_details.date as date',
                        'stock_transfer_details.bill_no',
                        'products.product_name',
                        'products.uom as uom',
                        'warehouses.name as warehouse_name',
                        'stock_transfer_details.sale_amount',
                        DB::raw('SUM(stock_transfer_details.qty_in) as qty_in'),
                        DB::raw('SUM(stock_transfer_details.qty_out) as qty_out')
                    )
                    ->whereDate('stock_transfer_details.date', '>=', $fromDate)
                    ->whereDate('stock_transfer_details.date', '<=', $toDate)
                    ->where('stock_transfer_details.warehouse_id', $warehouse_id)
                    // ->where('stock_transfer_details.product_id', $product_id)
                    // ->groupBy('stock_transfer_details.warehouse_id')
                    ->groupBy('stock_transfer_details.product_id')
                    ->orderBy('stock_transfer_details.date', 'asc')
                    ->get();

                return Response::json(['data' => $stockReportSummary, 'warehouse' => $warehouse]);
            } else {

                $stockReportDetailWise = StockTransferDetails::with('product:id,product_name,uom')
                    ->with('warehouse:id,name')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $product_id)
                    ->orderBy('date', 'asc')
                    ->get();

                return Response::json(['data' => $stockReportDetailWise, 'warehouse' => $warehouse]);
            }
        }


        $products = Product::where('warehouse_id', Auth::User()->warehouse_id)->pluck('product_name', 'id')->prepend('All', 0);
        $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
        // return view('inventory-reports.stock-transfer', compact('products', 'warehouses'));
        return view('inventory-reports.stock-transfer', compact('products', 'warehouses'));
    }


    
    // public function report(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $fromDate = $request->from_date;
    //         $toDate = $request->to_date;
    //         $product_id = $request->product_id;
    //         $warehouse_id = $request->warehouse_id;
    //         $report_type = $request->report_type;
    //         if($product_id==0){
    //         if ($report_type == "summary") {
    //             $stockReportSummaryWise = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
    //                 ->select(
    //                     'products.product_name',
    //                     DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
    //                     DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
    //                     'godown_stock_details.date'
    //                 )
    //                 ->where(function ($query) use ($warehouse_id) {
    //                     if ($warehouse_id == 0) {
    //                         $query->orderBy('godown_stock_details.warehouse_id')->get();
    //                     } else {
    //                         $query->where('godown_stock_details.warehouse_id', $warehouse_id)->get();
    //                     }
    //                 })
    //                 // ->where('godown_stock_details.product_id', $product_id)
    //                 ->whereDate('godown_stock_details.date', '>=', $fromDate)
    //                 ->whereDate('godown_stock_details.date', '<=', $toDate)
    //                 ->groupBy('godown_stock_details.product_id')
    //                 ->orderBy('godown_stock_details.product_id')
    //                 ->get();

    //             return Response::json($stockReportSummaryWise);
    //         } else if ($report_type == 'detailed') {
    //             $stockReportDetailWise = GodownStockDetail::with('product:id,product_name')
                   
    //                 ->where(function ($query) use ($warehouse_id) {
    //                     if ($warehouse_id == 0) {
    //                         $query->orderBy('warehouse_id')->get();
    //                     } else {
    //                         $query->where('warehouse_id', $warehouse_id)->get();
    //                     }
    //                     $query->with('warehouse:id,name');
    //                 })
    //                 // ->where('product_id', $product_id)
    //                 ->whereDate('date', '>=', $fromDate)
    //                 ->whereDate('date', '<=', $toDate)
    //                 ->orderBy('product_id')
    //                 ->get();

    //             return Response::json($stockReportDetailWise);
    //         }
    //       }else{
    //         if ($report_type == "summary") {
    //             $stockReportSummaryWise = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
    //                 ->select(
    //                     'products.product_name',
    //                     DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
    //                     DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
    //                     'godown_stock_details.date'
    //                 )
    //                 ->where(function ($query) use ($warehouse_id) {
    //                     if ($warehouse_id == 0) {
    //                         $query->orderBy('godown_stock_details.warehouse_id')->get();
    //                     } else {
    //                         $query->where('godown_stock_details.warehouse_id', $warehouse_id)->get();
    //                     }
    //                 })
    //                 ->where('godown_stock_details.product_id', $product_id)
    //                 ->whereDate('godown_stock_details.date', '>=', $fromDate)
    //                 ->whereDate('godown_stock_details.date', '<=', $toDate)
    //                 ->groupBy('godown_stock_details.product_id')
    //                 ->orderBy('godown_stock_details.product_id')
    //                 ->get();

    //             return Response::json($stockReportSummaryWise);
    //         } else if ($report_type == 'detailed') {
    //             $stockReportDetailWise = GodownStockDetail::with('product:id,product_name')
                   
    //                 ->where(function ($query) use ($warehouse_id) {
    //                     if ($warehouse_id == 0) {
    //                         $query->orderBy('warehouse_id')->get();
    //                     } else {
    //                         $query->where('warehouse_id', $warehouse_id)->get();
    //                     }
    //                     $query->with('warehouse:id,name');
    //                 })
    //                 ->where('product_id', $product_id)
    //                 ->whereDate('date', '>=', $fromDate)
    //                 ->whereDate('date', '<=', $toDate)
    //                 ->orderBy('product_id')
    //                 ->get();

    //             return Response::json($stockReportDetailWise);
    //         }
    //       }
    //     }

    //     $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('All Product', 0);
    //     $warehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->orderBy('name')->pluck('name', 'id')->prepend('All', 0);
    //     return view('stock-transfer.report', compact('products', 'warehouse'));
    // }
}