<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\SalePurchase;
use App\Models\SalePurchaseDetail;
use App\Models\GodownStockDetail;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Vouchers;
use App\Models\Warehouse;
use App\Models\StockDetails;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as DataTables;

class PurchaseReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $purchase = SalePurchase::whereType('PURCHASE RETURN')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->max('voucher_no');
        $codes = 1;
        if ($purchase) {
            $codes = (int)$purchase + 1;
        }

        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `product_price`, "_", `product_cost`, "_", `pack_type`, "_",`pack_weight`) AS `id`,`product_name`'))
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'asc')
        ->pluck('product_name', 'id')
        ->prepend('Select Product', '');

        $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
        ->where('role', '=', 'Supplier')
        // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        ->OrderBy('party_name', 'asc')
        ->pluck('party_name', 'id')
        ->prepend('Select Supplier', '');

        $warehouse = Warehouse::select(DB::raw('CONCAT(`id`, "_", `name`) AS `id`, `name`'))->OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Godown', '');
        // $transaction_type = array('' => 'Select Type', 'Cash' => 'Cash', 'Credit' => 'Credit');
        $transaction_type = array('' => 'SELECT TYPE', 'SUPPLIER' => 'SUPPLIER', 'PURCHASER' => 'PURCHASER');
        $suppliers = Party::whereRole('Supplier')->pluck('party_name', 'id')->prepend('Select Supplier', '');
        $purchasers = Party::whereRole('Purchaser')->pluck('party_name', 'id')->prepend('Select Purchaser', '');

        return view('purchase-return.index', compact('codes', 'customers', 'products', 'warehouse', 'transaction_type', 'suppliers', 'purchasers'));
    }

    public function LoadInvoices(Request $request){
        // return $request;
         $edit = SalePurchase::whereType('PURCHASE')
         ->where('warehouse_id', $request->warehouseID)
         ->where('voucher_no', $request->voucher_no)->first();
        if ($edit) {

             $edit = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product');
            }])
            ->with('party:id,party_name,address')
                ->where('id', $edit->id)
                ->where('warehouse_id', $request->warehouseID)
                ->whereType('PURCHASE')
                ->get();
            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function store(Request $request)
    {
        // return $request;
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
            'party_name' => 'required'
        ], [
            'date.required' => 'The Voucher Date field is required.'
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
        if (!isset($request->rate)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        $PurchaseWarehouse = Warehouse::where('id', $request->warehouse_id)->first();
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASE RETURN')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            // $count = count($request->st_rate);
            // $findtaxSum =0;
            // for ($i = 0; $i < $count; $i++) {
            //     if($request->st_rate[$i] > 0){
            //         $findtaxSum = $findtaxSum + 1;
            //     }
            // }
            $salePurchase = SalePurchase::find($request->update_voucher_id);

            $data = $request->all();
            // $data['warehouse_id'] = $request->warehouse_id[0];

            $data['updated_by'] = Auth::User()->id;
            $data['warehouse_id'] = $request->warehouse_id;
            // $data['created_by'] = Auth::User()->id;
            // $data['voucher_no']=$codes;
            $data['type']='PURCHASE RETURN';
            $data['party_id'] = $request->party_id;
            $data['purchaser_id'] = $request->purchaser_id;

            $salePurchase->update($data);

            SalePurchaseDetail::whereType('PURCHASE RETURN')->where('sale_purchase_id', $salePurchase->id)->delete();
            GeneralVoucher::where('v_type', 'PURCHASE RETURN')->where('voucher_id', $salePurchase->id)->delete();
            GodownStockDetail::where('type', 'PURCHASE RETURN')->where('transaction_id', $salePurchase->id)->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                // return "ent";
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id= $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'PURCHASE RETURN';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id=$request->product_id[$i];
                $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->excl_val[$i];
                // $salePurchaseDetails->st_rate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->st_rate = 0;
                $salePurchaseDetails->sale_tax = 0;
                $salePurchaseDetails->total = $request->excl_val[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;;
                $salePurchaseDetails->save();
                // return $request;

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $salePurchase->voucher_no;
                $godownstockDetails->transaction_id = $salePurchase->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date = $salePurchase->date;
                $godownstockDetails->type = 'PURCHASE RETURN';
                $godownstockDetails->warehouse_id = $request->warehouse_id;
                $godownstockDetails->party_id = $salePurchase->party_id;
                // $customerProduct = CustomerProduct::where('product_id', $request->product_id[$i])->first();
                // if($customerProduct){
                //     $godownstockDetails->product_id = $customerProduct->product_id;
                // }else{
                $godownstockDetails->product_id = $request->product_id[$i];
                // }
                // $godownstockDetails->warehouse_id = $request->warehouse_id;
                $godownstockDetails->qty_in = 0;
                // $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->demand_qty = 0;
                $godownstockDetails->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $godownstockDetails->remarks = $salePurchase->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = 0;
                $godownstockDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                if($request->credit_to == "SUPPLIER"){
                   $generalVoucher->account_head_id = $salePurchase->party_id;
               }
               if($request->credit_to == "PURCHASER"){
                   $generalVoucher->account_head_id = $salePurchase->purchaser_id;
               }
               //  $generalVoucher->account_head_id = $salePurchase->party_id;
            //    if($findtaxSum > 0){
            //        $generalVoucher->other_head_id = $PurchaseWarehouse->purchasetax_account_id;
            //    }else{
                   $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
            //    }
               //  $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
               //  $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
               //  $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->strate = 0;
                $generalVoucher->stvalue = 0;
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->v_type = 'PURCHASE RETURN';
                $generalVoucher->narration = 'PURCHASE RETURN';
               //  $generalVoucher->credit = $request->total[$i];
                $generalVoucher->debit = $request->excl_val[$i];
                $generalVoucher->save();


                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                // if($findtaxSum > 0){
                //     $generalVoucher->account_head_id = $PurchaseWarehouse->purchasetax_account_id;
                // }else{
                    $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                // }
                // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                if($request->credit_to == "SUPPLIER"){
                    $generalVoucher->other_head_id = $salePurchase->party_id;
                }
                if($request->credit_to == "PURCHASER"){
                    $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                }
                // $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                // $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                // $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->strate = 0;
                $generalVoucher->stvalue = 0;
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->v_type =  'PURCHASE RETURN';
                $generalVoucher->narration = 'PURCHASE RETURN';
                // $generalVoucher->debit = $request->total[$i];
                $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->save();

       

                // if($findtaxSum > 0){
                //     $generalVoucher = new GeneralVoucher();
                //     $generalVoucher->voucher_id = $salePurchase->id;
                //     if($findtaxSum > 0){
                //         $generalVoucher->account_head_id = $PurchaseWarehouse->tax_account_id;
                //     }else{
                //         $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                //     }
                //     // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                //     if($request->credit_to == "SUPPLIER"){
                //         $generalVoucher->other_head_id = $salePurchase->party_id;
                //     }
                //     if($request->credit_to == "PURCHASER"){
                //         $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                //     }
                //     // $generalVoucher->other_head_id = $salePurchase->party_id;
                //     $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                //     $generalVoucher->product_id =  $request->product_id[$i];
                //     $generalVoucher->date = $salePurchase->date;
                //     $generalVoucher->voucher_no = $salePurchase->voucher_no;
                //     $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                //     // $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                //     // $generalVoucher->stvalue = $request->sale_tax[$i];
                //     $generalVoucher->strate = 0;
                //     $generalVoucher->stvalue = 0;
                //     $generalVoucher->quantity = $request->qty[$i];
                //     $generalVoucher->v_type =  'PURCHASE RETURN';
                //     $generalVoucher->narration = 'PURCHASE RETURN';
                //     // $generalVoucher->debit = $request->total[$i];
                //     $generalVoucher->debit = $request->sale_tax[$i];
                //     $generalVoucher->save();
                // }

                

            }





            return redirect()->back()->with('flash_message', 'Purchase Return Voucher Updated Successfully!');
        } else {
              $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASE RETURN')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $count = count($request->st_rate);
            // $findtaxSum =0;
            // for ($i = 0; $i < $count; $i++) {
            //     if($request->st_rate[$i] > 0){
            //         $findtaxSum = $findtaxSum + 1;
            //     }
            // }
            // return $findtaxSum;
             $purchase = SalePurchase::whereType('PURCHASE RETURN')
            ->where('warehouse_id', $request->warehouse_id)
            ->max('voucher_no');
            $codes = 1;
            if ($purchase) {
                $codes = $purchase + 1;
            }
            // return $codes;
           $data = $request->all();
            // $data['voucher_no']=$codes;
            // $data['warehouse_id'] = $request->warehouse_id[0];
            // $data['created_by'] = Auth::User()->id;
            // $stock = Stock::create($data);
            // return $request;
            $data = $request->all();
            $data['warehouse_id'] = $request->warehouse_id;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no']=$codes;
            $data['type']='PURCHASE RETURN';
            $data['party_id'] = $request->party_id;
            $data['purchaser_id'] = $request->purchaser_id;
            $salePurchase = SalePurchase::create($data);
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                // return "ent";
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id= $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'PURCHASE RETURN';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id=$request->product_id[$i];
                $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->excl_val[$i];
                // $salePurchaseDetails->st_rate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->st_rate = 0;
                $salePurchaseDetails->sale_tax = 0;
                $salePurchaseDetails->total = $request->excl_val[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;;
                $salePurchaseDetails->save();
                // return $request;

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $salePurchase->voucher_no;
                $godownstockDetails->transaction_id = $salePurchase->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date = $salePurchase->date;
                $godownstockDetails->type = 'PURCHASE RETURN';
                $godownstockDetails->warehouse_id = $request->warehouse_id;
                $godownstockDetails->party_id = $salePurchase->party_id;
                // $customerProduct = CustomerProduct::where('product_id', $request->product_id[$i])->first();
                // if($customerProduct){
                //     $godownstockDetails->product_id = $customerProduct->product_id;
                // }else{
                $godownstockDetails->product_id = $request->product_id[$i];
                // }
                // $godownstockDetails->warehouse_id = $request->warehouse_id;
                $godownstockDetails->qty_in = 0;
                // $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->demand_qty = 0;
                $godownstockDetails->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $godownstockDetails->remarks = $salePurchase->remarks;
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = 0;
                $godownstockDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                if($request->credit_to == "SUPPLIER"){
                   $generalVoucher->account_head_id = $salePurchase->party_id;
               }
               if($request->credit_to == "PURCHASER"){
                   $generalVoucher->account_head_id = $salePurchase->purchaser_id;
               }
               //  $generalVoucher->account_head_id = $salePurchase->party_id;
               // if($findtaxSum > 0){
               //     $generalVoucher->other_head_id = $PurchaseWarehouse->purchasetax_account_id;
               // }else{
                   $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
               // }
               //  $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
               //  $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
               //  $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->strate = 0;
                $generalVoucher->stvalue = 0;
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->v_type = 'PURCHASE RETURN';
                $generalVoucher->narration = 'PURCHASE RETURN';
               //  $generalVoucher->credit = $request->total[$i];
                $generalVoucher->debit = $request->excl_val[$i];
                $generalVoucher->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                // if($findtaxSum > 0){
                //     $generalVoucher->account_head_id = $PurchaseWarehouse->purchasetax_account_id;
                // }else{
                    $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                // }
                // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                if($request->credit_to == "SUPPLIER"){
                    $generalVoucher->other_head_id = $salePurchase->party_id;
                }
                if($request->credit_to == "PURCHASER"){
                    $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                }
                // $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                // $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                // $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->strate = 0;
                 $generalVoucher->stvalue = 0;
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->v_type =  'PURCHASE RETURN';
                $generalVoucher->narration = 'PURCHASE RETURN';
                // $generalVoucher->debit = $request->total[$i];
                $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->save();

                // if($findtaxSum > 0){
                //     $generalVoucher = new GeneralVoucher();
                //     $generalVoucher->voucher_id = $salePurchase->id;
                //     if($findtaxSum > 0){
                //         $generalVoucher->account_head_id = $PurchaseWarehouse->tax_account_id;
                //     }else{
                //         $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                //     }
                //     // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                //     if($request->credit_to == "SUPPLIER"){
                //         $generalVoucher->other_head_id = $salePurchase->party_id;
                //     }
                //     if($request->credit_to == "PURCHASER"){
                //         $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                //     }
                //     // $generalVoucher->other_head_id = $salePurchase->party_id;
                //     $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                //     $generalVoucher->product_id =  $request->product_id[$i];
                //     $generalVoucher->date = $salePurchase->date;
                //     $generalVoucher->voucher_no = $salePurchase->voucher_no;
                //     $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                //     // $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                //     // $generalVoucher->stvalue = $request->sale_tax[$i];
                //     $generalVoucher->strate = 0;
                //     $generalVoucher->stvalue = 0;
                //     $generalVoucher->quantity = $request->qty[$i];
                //     $generalVoucher->v_type =  'PURCHASE RETURN';
                //     $generalVoucher->narration = 'PURCHASE RETURN';
                //     // $generalVoucher->debit = $request->total[$i];
                //     $generalVoucher->debit = $request->sale_tax[$i];
                //     $generalVoucher->save();
                // }

             

            }

            return redirect()->back()->with('flash_message', 'Purchase Return Voucher Added Successfully!');
        }
        abort(500);
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE RETURN')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $salePurchase = SalePurchase::whereType('PURCHASE RETURN')->where('voucher_no', $request->delete_voucher_no)->first();
        
        if ($salePurchase) {
            SalePurchase::findOrFail($salePurchase->id)->delete();
            SalePurchaseDetail::whereType('PURCHASE RETURN')->where('sale_purchase_id', $salePurchase->id)->delete();
            GeneralVoucher::where('v_type', 'PURCHASE RETURN')->where('voucher_id', $salePurchase->id)->delete();
            GodownStockDetail::where('type', 'PURCHASE RETURN')->where('transaction_id', $salePurchase->id)->delete();
            // $count = count($request->product_id);
            return redirect()->back()->with('flash_message', 'Purchase Return Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
         $purchaseVr = SalePurchase::whereType('PURCHASE RETURN')
        ->where('voucher_no', $request->voucher_no)
        ->where('warehouse_id', $request->warehouseID)
        ->first();

        if ($purchaseVr) {
            $data = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,code,product_name,uom');
            }])
            ->with('party:id,party_name,address')
                ->where('id', $purchaseVr->id)
                ->where('warehouse_id', $request->warehouseID)
                ->whereType('PURCHASE RETURN')
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $purchaseVr = SalePurchase::whereType('PURCHASE RETURN')
        ->where('voucher_no', '<', $request->voucher_no)
        ->where('warehouse_id', $request->warehouseID)
        ->max('voucher_no');
        $PurID = SalePurchase::whereType('PURCHASE RETURN')
        ->where('voucher_no', $purchaseVr)
        ->where('warehouse_id', $request->warehouseID)
        ->first();

        if ($PurID) {
              $data = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,code,product_name,uom');
            }])
            ->with('party:id,party_name,address')
                ->where('id', $PurID->id)
                ->where('warehouse_id', $request->warehouseID)
                ->whereType('PURCHASE RETURN')
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        // $purchase = Stock::whereType('Purchase Return')->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');
        $purchaseVr = SalePurchase::whereType('PURCHASE RETURN')
        ->where('voucher_no', '>', $request->voucher_no)
        ->where('warehouse_id', $request->warehouseID)
        ->min('voucher_no');
        $PurID = SalePurchase::whereType('PURCHASE RETURN')
        ->where('voucher_no', $purchaseVr)
        ->where('warehouse_id', $request->warehouseID)
        ->first();
        if ($PurID) {
            $data = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,code,product_name,uom');
            }])
            ->with('party:id,party_name,address')
                ->where('id', $PurID->id)
                ->where('warehouse_id', $request->warehouseID)
                ->whereType('PURCHASE RETURN')
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function report(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE RETURN')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        if ($request->ajax()) {
            if ($request->report_type == 'summary') {
                $purchase = Purchase::with('parties')
                    ->where('purchase_type', 'Purchase')
                    ->where('party_id', $request->party_id)
                    ->whereDate('date', '>=', $request->from_date)
                    ->whereDate('date', '<=', $request->to_date)
                    ->get();
                return DataTables::of($purchase)
                    ->addIndexColumn()
                    ->addColumn('date', function ($row) {
                        $date = date('d/m/Y', strtotime($row->date));
                        return $date;
                    })
                    ->addColumn('party_id', function ($data) {
                        $party = $data->parties->party_name;
                        return $party;
                    })
                    ->make(true);
            }
        }


        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');
        return view('purchases.report', compact('customers'));
    }
    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() .'/upload/purchase-return');
        // $voucher_no = $request->voucher_no;
        // $purchase = Stock::where('type', 'Purchase Return')->where('voucher_no', $voucher_no)->first();
        $purchase = SalePurchase::whereType('PURCHASE RETURN')
        ->where('voucher_no', $request->voucher_no)
        ->where('warehouse_id', $request->warehouseID)
        ->first();
        if ($purchase) {
            $purchaseDetails = SalePurchase::with(['sale_purchase_details' => function($query){
                $query->with('product:id,code,product_name,uom');
            }])
            ->with('party:id,party_name,address')
                ->where('id', $purchase->id)
                ->where('warehouse_id', $request->warehouseID)
                ->whereType('PURCHASE RETURN')
                ->get();

            $pdf = PDF::loadView('purchase-return.invoice', compact('purchaseDetails'));
            $fileName =  'purchase-return-' . $purchase->voucher_no . '.pdf';
            $pdf->save(base_path('upload/purchase-return/' . $fileName));
            return $fileName;
        } else{
            return false;
        }
    }
}