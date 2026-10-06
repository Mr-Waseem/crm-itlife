<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Product;
use App\Models\Party;
use App\Models\Warehouse;
use App\Models\GodownStock;
use Illuminate\Http\Request;
use App\Models\GodownStockDetail;
use App\Models\SalePurchaseDetail;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;
class IssuanceReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        //     ->where('voucher_name', 'ISSUANCE RETURN')
        //     // ->where('right_name', 'SHOW')
        //     ->get();
        $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
        ->where('type','ISSUANCE RETURN')
        ->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }

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
            //  $accounts = Party::
            // whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            // ->whereNotIN('account_type', ['SUPPLIER', 'PURCHASER', 'CUSTOMER'])
            //   ->OrderBy('party_name', 'asc')
            //   ->pluck('party_name', 'id');

            $suppliers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('account_type', 'SUPPLIER')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

            $accounts = Party::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `party_name`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Account', '')
            ->toArray();

    //    return $StockDetailProduct=Product::join('godown_stock_details','godown_stock_details.product_id','=','products.id')
    //         ->where('godown_stock_details.warehouse_id',Auth::User()->warehouse_id)
    //          ->where('godown_stock_details.type','STOCK TRANSFER')
    //          ->where('godown_stock_details.qty_out','!=',0)
    //          ->distinct('godown_stock_details.product_id')
    //          ->pluck('products.product_name', 'products.id');
           
        $warehouseFrom = Warehouse::select('name')->where('id', Auth::User()->warehouse_id)->first();
        $warehouseTo = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');

        return view('stock-transfer.issuance-return.index', compact('products', 'codes', 'warehouseFrom', 'warehouseTo', 'StockProduct', 'accounts'));
    }

    public function edit($PurchaseID){
        $data = GodownStock::whereId($PurchaseID)->first();
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
        //  $accounts = Party::
        // whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        // ->whereNotIN('account_type', ['SUPPLIER', 'PURCHASER', 'CUSTOMER'])
        //   ->OrderBy('party_name', 'asc')
        //   ->pluck('party_name', 'id');

        $suppliers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('account_type', 'SUPPLIER')
        ->OrderBy('party_name', 'asc')
        ->pluck('party_name', 'id')
        ->prepend('Select Party Name', '');

        $accounts = Party::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `party_name`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
        ->OrderBy('party_name', 'asc')
        ->pluck('party_name', 'id')
        ->prepend('Select Account', '')
        ->toArray();

//    return $StockDetailProduct=Product::join('godown_stock_details','godown_stock_details.product_id','=','products.id')
//         ->where('godown_stock_details.warehouse_id',Auth::User()->warehouse_id)
//          ->where('godown_stock_details.type','STOCK TRANSFER')
//          ->where('godown_stock_details.qty_out','!=',0)
//          ->distinct('godown_stock_details.product_id')
//          ->pluck('products.product_name', 'products.id');
       
    $warehouseFrom = Warehouse::select('name')->where('id', Auth::User()->warehouse_id)->first();
    $warehouseTo = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');

    return view('stock-transfer.issuance-return.index', compact('products', 'data', 'warehouseFrom', 'warehouseTo', 'StockProduct', 'accounts'));
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
        $purchaseWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->first();
        if ($request->update_voucher_no != null || $request->update_voucher_no != 0) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ISSUANCE RETURN')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $stockTransfer = GodownStock::where('voucher_no', $request->update_voucher_no)->where('type','ISSUANCE RETURN')->first();
            $stockTransfer = GodownStock::findOrFail($stockTransfer->id);
            $stockTransfer->update($request->all());

           
            GodownStockDetail::where('voucher_no', $request->update_voucher_no)->where('type','ISSUANCE RETURN')->delete();
            GeneralVoucher::where('voucher_no', $request->update_voucher_no)->where('v_type','ISSUANCE RETURN')->delete();
            $count = count($request->product_id);
            $totalQty = 0;
            $totalAmount = 0;
            for ($i = 0; $i < $count; $i++) {
                $stockTransferDetails = new GodownStockDetail();
                $stockTransferDetails->transaction_id = $stockTransfer->id;
                $stockTransferDetails->date = $stockTransfer->date;
                $stockTransferDetails->voucher_no = $stockTransfer->voucher_no;
                $stockTransferDetails->product_id = $request->product_id[$i];
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->party_id = $request->account_id[$i];
                $stockTransferDetails->qty_out = 0;
                $stockTransferDetails->qty_in = $request->qty[$i];
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $stockTransfer->id;
                $purchaseDetail->account_head_id = $request->account_id[$i];
                $purchaseDetail->other_head_id = $purchaseWarehouse->purchase_account_id;
                $purchaseDetail->date = $stockTransfer->date;
                $purchaseDetail->voucher_no = $stockTransfer->voucher_no;
                $purchaseDetail->v_type = $stockTransfer->type;
                $purchaseDetail->narration = $stockTransfer->remarks;
                $purchaseDetail->credit = $request->total[$i];
                $purchaseDetail->save();

                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $stockTransfer->id;
                $purchaseDetail->account_head_id = $purchaseWarehouse->purchase_account_id;
                $purchaseDetail->other_head_id = $request->account_id[$i];
                $purchaseDetail->date = $stockTransfer->date;
                $purchaseDetail->voucher_no = $stockTransfer->voucher_no;
                $purchaseDetail->v_type = $stockTransfer->type;
                $purchaseDetail->narration = $stockTransfer->remarks;
                $purchaseDetail->debit = $request->total[$i];
                $purchaseDetail->save();

            }
          
            $stockTransfer->created_by = Auth::User()->id;
            $stockTransfer->updated_by = Auth::User()->id;
            $stockTransfer->save();
            Session::flash('flash_message', 'Issuance Return Updated Successfully!');
            return redirect('issuance-return');
            // return redirect()->back()->with('flash_message', 'Issuance Return Updated Successfully!');
        } else {
            // return $request;
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ISSUANCE RETURN')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            // return $request;
         $code = GodownStock::where('from_warehouse_id', Auth::User()->warehouse_id)
         ->where('type','ISSUANCE RETURN')->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->voucher_no + 1;
        }
        $data = $request->all();
        $data['voucher_no'] = $codes;
        $data['status'] =0;
        $data['type'] = "ISSUANCE RETURN";
        $data['from_warehouse_id'] =Auth::User()->warehouse_id;
        $data['created_by'] = Auth::User()->id;
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
                $stockTransferDetails->warehouse_id = $stockTransfer->to_warehouse_id;
                $stockTransferDetails->party_id = $request->account_id[$i];
                $stockTransferDetails->qty_out = 0;
                $stockTransferDetails->qty_in =$request->qty[$i];
                $stockTransferDetails->rate = $request->price[$i];
                $stockTransferDetails->amount = $request->total[$i];
                $stockTransferDetails->type = $stockTransfer->type;
                $stockTransferDetails->created_by = Auth::User()->id;
                $stockTransferDetails->save();

                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $stockTransfer->id;
                $purchaseDetail->account_head_id = $request->account_id[$i];
                $purchaseDetail->other_head_id = $purchaseWarehouse->purchase_account_id;
                $purchaseDetail->date = $stockTransfer->date;
                $purchaseDetail->voucher_no = $stockTransfer->voucher_no;
                $purchaseDetail->v_type = $stockTransfer->type;
                $purchaseDetail->narration = $stockTransfer->remarks;
                $purchaseDetail->credit = $request->total[$i];
                $purchaseDetail->save();

                $purchaseDetail = new GeneralVoucher();
                $purchaseDetail->voucher_id = $stockTransfer->id;
                $purchaseDetail->account_head_id = $purchaseWarehouse->purchase_account_id;
                $purchaseDetail->other_head_id = $request->account_id[$i];
                $purchaseDetail->date = $stockTransfer->date;
                $purchaseDetail->voucher_no = $stockTransfer->voucher_no;
                $purchaseDetail->v_type = $stockTransfer->type;
                $purchaseDetail->narration = $stockTransfer->remarks;
                $purchaseDetail->debit = $request->total[$i];
                $purchaseDetail->save();

            }
            Session::flash('flash_message', 'Issuance Return Added Successfully!');
            return redirect('issuance-return');
            return redirect()->back()->with('flash_message', 'Issuance Return Added Successfully!');
        }
        abort(500);
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
    public function destroy(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ISSUANCE RETURN')
                ->where('right_name', 'DELETE')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
        $delete = GodownStock::where('voucher_no', $request->delete_invoice_no)->where('type','ISSUANCE RETURN')->where('from_warehouse_id',Auth::User()->warehouse_id)->first();
        if ($delete) {
            GodownStock::findOrFail($delete->id)->delete();
            GodownStockDetail::where('transaction_id', $delete->id)->where('type','ISSUANCE RETURN')->delete();
            // GodownStockDetail::where('voucher_no', $request->update_voucher_no)->where('type','ISSUANCE')->delete();
            GeneralVoucher::where('voucher_id', $delete->id)->where('v_type','ISSUANCE RETURN')->delete();
            Session::flash('flash_message', 'Issuance Return Deleted Successfully!');
            return redirect('issuance-return');
            // return redirect()->back()->with('flash_message', 'Issuance Return Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $GodownStock = GodownStock::where('voucher_no', $request->voucher_no)
        ->where('type','ISSUANCE RETURN')->where('from_warehouse_id',Auth::User()->warehouse_id)->first();
        if ($GodownStock) {
            $GodownStockDetail = GodownStockDetail::with('product','godownstock:id,remarks,to_warehouse_id')->where('voucher_no', $request->voucher_no)
            ->with('party:id,code,party_name')
            ->where('type','ISSUANCE RETURN')
            // ->where('warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_out','!=',null)
            ->get();
            return Response::json(['data' => $GodownStockDetail]);
        } else {
            return Response::json(['data' => '']);
        }
        abort(500);
    }

    public function LoadNextData(Request $request)
    {
        $godownStock = GodownStock::where('voucher_no', '>', $request->voucher_no)->where('type','ISSUANCE RETURN')->where('from_warehouse_id',Auth::User()->warehouse_id)->min('voucher_no');
        if ($godownStock) {
            $godownStockDetail = GodownStockDetail::with('product','godownstock:id,remarks,to_warehouse_id')
            ->with('party:id,code,party_name')
            ->where('voucher_no', $godownStock)
            ->where('type','ISSUANCE RETURN')
            // ->where('warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_out','!=',null)
            ->get();
            return Response::json(['data' => $godownStockDetail]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
         $GodownStock = GodownStock::where('voucher_no', '<', $request->voucher_no)->where('type','ISSUANCE RETURN')
        ->where('from_warehouse_id',Auth::User()->warehouse_id)->max('voucher_no');
        if ($GodownStock) {
             $GodownStockDetail = GodownStockDetail::with('product','godownstock:id,remarks,to_warehouse_id')
            ->with('party:id,code,party_name')
            ->where('voucher_no', $GodownStock)
            ->where('type','ISSUANCE RETURN')
            // ->where('warehouse_id',Auth::User()->warehouse_id)
            // ->where('qty_in','!=',null)
            ->get();
        
            return Response::json(['data' => $GodownStockDetail]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function productRecord(Request $request)
    { 
        // return $request;
        // return $openingstock = GodownStockDetail::where('type', 'OPENING STOCK')->count();

         $openingvalues = GodownStockDetail::select(
                DB::raw('SUM(qty_in) as openingqty'),
                DB::raw('SUM(amount) as openingamount'),
            )
        ->where('type', 'OPENING STOCK')
        ->where('product_id', $request->product_id)
        // ->whereDate('date', '>=', $fromDate)
        // ->whereDate('date', '<=', $toDate)
        ->get();

        $GRNvalues = SalePurchaseDetail::select(
            DB::raw('SUM(qty) as purchaseqty'),
            DB::raw('SUM(total) as purchasamount'),
        )
        ->where('type', 'PURCHASE')
        ->where('product_id', $request->product_id)
        // ->whereDate('date', '>=', $fromDate)
        ->whereDate('date', '<=', $request->issueDate)
        ->get();

         $issuevalues = GodownStockDetail::select(
            DB::raw('SUM(qty_out) as issueqty'),
            DB::raw('SUM(amount) as issueamount'),
        )
    ->where('type', 'ISSUANCE')
    ->where('product_id', $request->product_id)
    ->whereDate('date', '<=', $request->issueDate)
    ->get();

        $totalqty = $openingvalues[0]->openingqty + $GRNvalues[0]->purchaseqty + $issuevalues[0]->issueqty;
        $totalamount = $openingvalues[0]->openingamount + $GRNvalues[0]->purchasamount + $issuevalues[0]->issueamount;
       if($totalqty > 0){
        $WeightedAvg = $totalamount/$totalqty;
       }else{
        $WeightedAvg = 0;
       }
         

         $Product = Product::where('id', $request->product_id)->get(['id','code','product_name','uom','product_price','product_cost', 'packing']);
         return Response::json(['Product' => $Product, 'WeightedAvg' => $WeightedAvg]);
        //  return json_encode($Product);
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
        $GodownStock = GodownStock::where('type','ISSUANCE RETURN')->where('from_warehouse_id',Auth::User()->warehouse_id)->where('voucher_no', $voucher_no)->first();
        if ($GodownStock) {
            $godownStockDetail = GodownStockDetail::with(['godownstock'=>function($qry){
                            $qry->with('warehouse_from:id,name','warehouse_to:id,name');
                            
            }])
                ->with('product:id,code,product_name,uom')
                ->with('party:id,code,party_name')
                ->where('voucher_no', $voucher_no)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('type','ISSUANCE RETURN')
                // ->where('qty_out','!=', null)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('stock-transfer.issuance-return.invoice', compact('godownStockDetail'));
            $fileName =  'issuance' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/stock-transfer/' . $fileName));
            return $fileName;
        } else{
            return false;  
        }
    }
}