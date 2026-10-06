<?php

namespace App\Http\Controllers;

use App\Models\BatchStock;
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

class BatchStockController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return "dd";
         $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)->where('type','BATCH STOCK TRANSFER')->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }

        //  $products = Product::where('warehouse_id', Auth::User()->warehouse_id)
        //     // ->where('product_type','Finish')
        //     ->OrderBy('id', 'asc')
        //     ->pluck('product_name', 'id');
         $RoleOnly = DB::table('productions')
        ->join('products','products.id','=','productions.product_id')
        ->select(DB::raw("CONCAT(productions.id, '_', productions.batchNo, '_', productions.product_id, '_', products.code, '_', products.product_name) AS  my_no ,CONCAT(productions.batchNo, '-', productions.thickness, '-', productions.width, '-', productions.total_qty) AS  voucher_no")) 
        ->where('productions.p_status', '0')
        ->where('productions.p_type', 'PRODUCTION')
        ->pluck('voucher_no','my_no')
        ->prepend('Select Product', '0');

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

            $batchStock = BatchStock::with('color')->with('product')
            //  ->where('p_type', 'PRODUCTION')
             ->where('warehouse_id', Auth::User()->warehouse_id)
             ->where('total_qty', '>', '0')
             ->where('p_status', '0')
             ->OrderBy('id', 'asc')->get();
           

    //    return $StockDetailProduct=Product::join('godown_stock_details','godown_stock_details.product_id','=','products.id')
    //         ->where('godown_stock_details.warehouse_id',Auth::User()->warehouse_id)
    //          ->where('godown_stock_details.type','STOCK TRANSFER')
    //          ->where('godown_stock_details.qty_out','!=',0)
    //          ->distinct('godown_stock_details.product_id')
    //          ->pluck('products.product_name', 'products.id');
           
        $warehouseFrom = Warehouse::select('name')->where('id', Auth::User()->warehouse_id)->first();
        $warehouseTo = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');

        return view('stock-transfer.batch.index', compact('products', 'codes', 'warehouseFrom', 'warehouseTo', 'StockProduct', 'batchStock'));
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
    //    return $count = count($request->product_id);
    //         $testarray = [];
    //         for ($i = 0; $i < $count; $i++) {
                
    //             $testarray[$i] =  $request->batchChecked[$i] ?? null;

    //         }
    //         return $testarray;
            // dd($testarray);
        $Validator = Validator::make($request->all(), [
            'date' => 'required|date',
            'voucher_no' => 'required',
            // 'from_warehouse_id' => 'required',
            'to_warehouse_id' => 'required'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator)->withInput();
        }
        // if (!isset($request->batch_id)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }

        // if (!isset($request->total_qty)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
        
        if ($request->update_voucher_no != null || $request->update_voucher_no != 0) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'BATCH STOCK TRANSFER')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $stockTransfer = GodownStock::where('voucher_no', $request->update_voucher_no)->where('type','STOCK TRANSFER')->first();
            $stockTransfer = GodownStock::findOrFail($stockTransfer->id);
            $stockTransfer->update($request->all());

           
            GodownStockDetail::where('voucher_no', $request->update_voucher_no)->where('type','STOCK TRANSFER')->delete();
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
                $stockTransferDetails->qty_out = $request->total_qty[$i];
                $stockTransferDetails->qty_in =0;
                // $stockTransferDetails->rate = $request->price[$i];
                // $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $request->total_qty[$i];
                $stockTransferDetails->qty_out =0;
                // $stockTransferDetails->rate = $request->price[$i];
                // $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

            }
          
            $stockTransfer->created_by = Auth::User()->id;
            $stockTransfer->updated_by = Auth::User()->id;
            $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Stock Transfer Updated Successfully!');
        } else {
            // return $request;
             $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'BATCH STOCK TRANSFER')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            //   return $request;
         $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)->where('type','BATCH STOCK TRANSFER')->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        
        $data = $request->all();
        $data['voucher_no'] = $codes;
        $data['status'] =0;
        $data['from_warehouse_id'] = Auth::User()->warehouse_id;
        $data['created_by'] = Auth::User()->id;
        // return $request;
         $stockTransfer = GodownStock::create($data);
         $batchData = BatchStock::wherein('id', $request->batchStockID)->get();
        //  return $batchData;
            $count = count($request->batchStockID);
            $totalQty = 0;
            $totalAmount = 0;
            for ($i = 0; $i < $count; $i++) {
                //  return $batchData;
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $batchData[$i]->product_id;
                $stockTransferDetails->warehouse_id = $stockTransfer->from_warehouse_id;
                $stockTransferDetails->qty_out = $batchData[$i]->total_qty;
                $stockTransferDetails->qty_in =0;
                // $stockTransferDetails->rate = $request->price[$i];
                // $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $batchData[$i]->product_id;
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->qty_in = $batchData[$i]->total_qty;
                $stockTransferDetails->qty_out =0;
                // $stockTransferDetails->rate = $request->price[$i];
                // $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

                $batchStock = new BatchStock();
                $batchStock->transaction_id = $stockTransfer->id;
                $batchStock->voucher_no = $stockTransfer->voucher_no;
                $batchStock->date = $stockTransfer->date;
                $batchStock->product_id  = $batchData[$i]->product_id;
                $batchStock->recipe_id = $batchData[$i]->recipe_id;
                $batchStock->unit_id  = $batchData[$i]->unit_id;
                $batchStock->warehouse_id  = $stockTransfer->from_warehouse_id;
                $batchStock->from_warehouse_id  = Auth::User()->warehouse_id;
                $batchStock->to_warehouse_id  = $stockTransfer->to_warehouse_id;
                $batchStock->total_qty  = 0;
                $batchStock->out_qty  = $batchData[$i]->total_qty;
                $batchStock->gross_weight  = $batchData[$i]->gross_weight;
                $batchStock->total_rate  = $batchData[$i]->total_rate;
                $batchStock->total_amount  = $batchData[$i]->total_amount;
                $batchStock->remarks  = $stockTransfer->remarks;
                $batchStock->color_id  = $batchData[$i]->color_id;
                $batchStock->machine_id  = $batchData[$i]->machine_id;
                $batchStock->shift_id  = $batchData[$i]->shift_id;
                $batchStock->forman_id  = $batchData[$i]->forman_id;
                $batchStock->operator_id  = $batchData[$i]->operator_id;
                $batchStock->thickness  = $batchData[$i]->thickness;
                $batchStock->width  = $batchData[$i]->width;
                $batchStock->batchNo  = $batchData[$i]->batchNo;
                $batchStock->p_status  = $batchData[$i]->p_status;
                $batchStock->p_type  = $stockTransfer->type;
                // $batchStock->balance_weight  = $request->balance_weight[$i];
                $batchStock->balance_weight  = $batchData[$i]->balance_weight;
                $batchStock->created_by  = $batchData[$i]->created_by;
                $batchStock->save();

                $batchStock = new BatchStock();
                $batchStock->transaction_id = $stockTransfer->id;
                $batchStock->voucher_no = $stockTransfer->voucher_no;
                $batchStock->date = $stockTransfer->date;
                $batchStock->product_id  = $batchData[$i]->product_id;
                $batchStock->recipe_id = $batchData[$i]->recipe_id;
                $batchStock->unit_id  = $batchData[$i]->unit_id;
                $batchStock->warehouse_id  = $stockTransfer->to_warehouse_id;
                // $batchStock->warehouse_id  = $stockTransfer->from_warehouse_id;
                $batchStock->from_warehouse_id  = Auth::User()->warehouse_id;
                $batchStock->to_warehouse_id  = $stockTransfer->to_warehouse_id;
                $batchStock->total_qty  = $batchData[$i]->total_qty;
                $batchStock->out_qty  = 0;
                $batchStock->gross_weight  = $batchData[$i]->gross_weight;
                $batchStock->total_rate  = $batchData[$i]->total_rate;
                $batchStock->total_amount  = $batchData[$i]->total_amount;
                $batchStock->remarks  = $stockTransfer->remarks;
                $batchStock->color_id  = $batchData[$i]->color_id;
                $batchStock->machine_id  = $batchData[$i]->machine_id;
                $batchStock->shift_id  = $batchData[$i]->shift_id;
                $batchStock->forman_id  = $batchData[$i]->forman_id;
                $batchStock->operator_id  = $batchData[$i]->operator_id;
                $batchStock->thickness  = $batchData[$i]->thickness;
                $batchStock->width  = $batchData[$i]->width;
                $batchStock->batchNo  = $batchData[$i]->batchNo;
                $batchStock->p_status  = $batchData[$i]->p_status;
                $batchStock->p_type  = $stockTransfer->type;
                // $batchStock->balance_weight  = $request->balance_weight[$i];
                $batchStock->balance_weight  = $batchData[$i]->balance_weight;
                $batchStock->created_by  = $batchData[$i]->created_by;
                $batchStock->save();

                $batchData[$i]->p_status = 1;
                $batchData[$i]->save();
            }
            // return "d";
            $stockTransfer->created_by = Auth::User()->id;
            $stockTransfer->save();

            return redirect()->back()->with('flash_message', 'Batch Stock Transfer Added Successfully!');
        }
        abort(500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BatchStock  $batchStock
     * @return \Illuminate\Http\Response
     */
    public function show(BatchStock $batchStock)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\BatchStock  $batchStock
     * @return \Illuminate\Http\Response
     */
    public function edit(BatchStock $batchStock)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BatchStock  $batchStock
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BatchStock $batchStock)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BatchStock  $batchStock
     * @return \Illuminate\Http\Response
     */
    public function destroy(BatchStock $batchStock)
    {
        //
    }
}
