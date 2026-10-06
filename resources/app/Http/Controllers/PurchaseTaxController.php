<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\GodownStock;
use App\Models\SalePurchase;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\GodownStockDetail;
use App\Models\SalePurchaseDetail;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Session;
class PurchaseTaxController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $purchase = SalePurchase::whereType('PURCHASETAX')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($purchase) {
            $codes = (int)$purchase->voucher_no + 1;
        }
        // return $codes;
             $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `product_price`, "_", `product_cost`, "_", `pack_type`, "_",`pack_weight`) AS `id`,`product_name`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
           $Grn = GodownStock::join('inward_gate_passes', 'inward_gate_passes.id', '=', 'godown_stocks.inward_gatepass_id')
         ->join('request_generates', 'request_generates.id', '=', 'inward_gate_passes.req_gen_id')
         //  ->whereStatus(0)->where('type','GRN')
        //  ->select(
        //     DB::raw('CONCAT(`godown_stocks.voucher_no`,"-",`godown_stocks.voucher_no`) `product_name`')
        //     )
      
        //  ->select(
        //      DB::raw('CONCAT(`voucher_no`) as data'),
             
        // )
         ->where('request_generates.type', 'PO')
         ->where('godown_stocks.status', 0)
        
        // ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
         ->pluck('godown_stocks.voucher_no','godown_stocks.id')
        //  ->pluck('godown_stocks.voucher_no', 'godown_stocks.id')
        //  ->get();
         
        //  ->pluck('godown_stocks.voucher_no', 'godown_stocks.id')
         ->prepend('Select Grn', '');

        // $Grn = GodownStock::whereStatus(0)->where('type','GRN')->pluck('voucher_no', 'id')->prepend('Select Grn', '');
         $warehouse=Warehouse::where('id',Auth::User()->warehouse_id)->pluck('name','id');
    
        return view('purchases.purchase-tax.index', compact('codes', 'products', 'warehouse', 'Grn'));
    }

    public function edit($PurchaseID){
        $data = SalePurchase::whereId($PurchaseID)->first();
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `product_price`, "_", `product_cost`, "_", `pack_type`, "_",`pack_weight`) AS `id`,`product_name`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
           $Grn = GodownStock::join('inward_gate_passes', 'inward_gate_passes.id', '=', 'godown_stocks.inward_gatepass_id')
         ->join('request_generates', 'request_generates.id', '=', 'inward_gate_passes.req_gen_id')
         //  ->whereStatus(0)->where('type','GRN')
        //  ->select(
        //     DB::raw('CONCAT(`godown_stocks.voucher_no`,"-",`godown_stocks.voucher_no`) `product_name`')
        //     )
      
        //  ->select(
        //      DB::raw('CONCAT(`voucher_no`) as data'),
             
        // )
         ->where('request_generates.type', 'PO')
         ->where('godown_stocks.status', 0)
        
        // ->select(DB::raw("id,CONCAT(code, '-', party_name) AS  voucher_no")) 
         ->pluck('godown_stocks.voucher_no','godown_stocks.id')
        //  ->pluck('godown_stocks.voucher_no', 'godown_stocks.id')
        //  ->get();
         
        //  ->pluck('godown_stocks.voucher_no', 'godown_stocks.id')
         ->prepend('Select Grn', '');

        // $Grn = GodownStock::whereStatus(0)->where('type','GRN')->pluck('voucher_no', 'id')->prepend('Select Grn', '');
         $warehouse=Warehouse::where('id',Auth::User()->warehouse_id)->pluck('name','id');
    
        return view('purchases.purchase-tax.index', compact('data', 'products', 'warehouse', 'Grn'));
    }

    public function store(Request $request)
    {  
        // return $request;
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
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
        $PurchaseWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->first();
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASE TAX VOUCHER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //find tax value for hidden account credit/debit

            $count = count($request->st_rate);
            $findtaxSum =0;
            for ($i = 0; $i < $count; $i++) {
                if($request->st_rate[$i] > 0){
                    $findtaxSum = $findtaxSum + 1;
                }
            }
            // return $findtaxSum;
            $salePurchase = SalePurchase::where('id',$request->update_voucher_id)
            ->where('type','PURCHASETAX')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->first();

            $data = $request->all();
            $data['type']='PURCHASETAX';
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['updated_by'] = Auth::User()->id;
            $data['party_id'] = $request->supplier_id1;
            $data['purchaser_id'] = $request->purchaser_id1;
            $data['grn_dc_id'] = $request->grn_id1;
            $salePurchase->update($data);

            SalePurchaseDetail::whereType('PURCHASETAX')->where('sale_purchase_id', $salePurchase->id)->delete();
            GeneralVoucher::where('v_type', 'PURCHASETAX')->where('voucher_id', $salePurchase->id)->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id= $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'PURCHASETAX';
                $salePurchaseDetails->warehouse_id = Auth::User()->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id=$request->product_id[$i];
                // $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->excl_val[$i];
                $salePurchaseDetails->st_rate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                // $request->rate[$i] == null ? 0 : $request->rate[$i];
                $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;;
                $salePurchaseDetails->save();

                //
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                if($findtaxSum > 0){
                    $generalVoucher->account_head_id = $PurchaseWarehouse->purchasetax_account_id;
                }else{
                    $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                }
                // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                if($request->credit_to == "SUPPLIER"){
                    $generalVoucher->other_head_id = $salePurchase->party_id;
                }
                if($request->credit_to == "PURCHASER"){
                    $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                }
                // $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->v_type =  'PURCHASETAX';
                $generalVoucher->narration = 'PURCHASETAX INVOICE';
                // $generalVoucher->debit = $request->total[$i];
                $generalVoucher->debit = $request->excl_val[$i];
                $generalVoucher->save();

                if($findtaxSum > 0){
                    $generalVoucher = new GeneralVoucher();
                    $generalVoucher->voucher_id = $salePurchase->id;
                    if($findtaxSum > 0){
                        $generalVoucher->account_head_id = $PurchaseWarehouse->tax_account_id;
                    }else{
                        $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                    }
                    // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                    if($request->credit_to == "SUPPLIER"){
                        $generalVoucher->other_head_id = $salePurchase->party_id;
                    }
                    if($request->credit_to == "PURCHASER"){
                        $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                    }
                    // $generalVoucher->other_head_id = $salePurchase->party_id;
                    $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                    $generalVoucher->product_id =  $request->product_id[$i];
                    $generalVoucher->date = $salePurchase->date;
                    $generalVoucher->voucher_no = $salePurchase->voucher_no;
                    $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                    $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                    $generalVoucher->stvalue = $request->sale_tax[$i];
                    $generalVoucher->quantity = $request->qty[$i];
                    $generalVoucher->v_type =  'PURCHASETAX';
                    $generalVoucher->narration = 'PURCHASETAX INVOICE';
                    // $generalVoucher->debit = $request->total[$i];
                    $generalVoucher->debit = $request->sale_tax[$i];
                    $generalVoucher->save();
                }
                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 if($request->credit_to == "SUPPLIER"){
                    $generalVoucher->account_head_id = $salePurchase->party_id;
                }
                if($request->credit_to == "PURCHASER"){
                    $generalVoucher->account_head_id = $salePurchase->purchaser_id;
                }
                //  $generalVoucher->account_head_id = $salePurchase->party_id;
                if($findtaxSum > 0){
                    $generalVoucher->other_head_id = $PurchaseWarehouse->purchasetax_account_id;
                }else{
                    $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
                }
                //  $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
                 $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                 $generalVoucher->product_id =  $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                 $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                 $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                 $generalVoucher->stvalue = $request->sale_tax[$i];
                 $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->v_type = 'PURCHASETAX';
                 $generalVoucher->narration = 'PURCHASETAX INVOICE';
                 $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->save();
            }
            Session::flash('flash_message', 'Purchase Tax Voucher Updated Successfully!');
            return redirect('purchase-tax');
            return redirect()->back()->with('flash_message', 'Purchase Tax Voucher Updated Successfully!');
        } else {
          
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PURCHASE TAX VOUCHER')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //find tax value for hidden account credit/debit
            $count = count($request->st_rate);
            $findtaxSum =0;
            for ($i = 0; $i < $count; $i++) {
                if($request->st_rate[$i] > 0){
                    $findtaxSum = $findtaxSum + 1;
                }
            }
            // return $findtaxSum;
            $purchase = SalePurchase::whereType('PURCHASETAX')
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'desc')->first();
            $codes = 1;
            if ($purchase) {
                $codes = (int)$purchase->voucher_no + 1;
            }

            $data = $request->all();
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no']=$codes;
            $data['type']='PURCHASETAX';
            $data['grn_dc_id']=$request->grn_id;
            $data['party_id'] = $request->supplier_id1;
            $data['purchaser_id'] = $request->purchaser_id1;
            $salePurchase = SalePurchase::create($data);


            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id= $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'PURCHASETAX';
                $salePurchaseDetails->warehouse_id = Auth::User()->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id=$request->product_id[$i];
                // $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->excl_val[$i];
                $salePurchaseDetails->st_rate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;;
                $salePurchaseDetails->save();
                // return $request;
                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                if($findtaxSum > 0){
                    $generalVoucher->account_head_id = $PurchaseWarehouse->purchasetax_account_id;
                }else{
                    $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                }
                // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                if($request->credit_to == "SUPPLIER"){
                    $generalVoucher->other_head_id = $salePurchase->party_id;
                }
                if($request->credit_to == "PURCHASER"){
                    $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                }
                // $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->v_type =  'PURCHASETAX';
                $generalVoucher->narration = 'PURCHASETAX INVOICE';
                // $generalVoucher->debit = $request->total[$i];
                $generalVoucher->debit = $request->excl_val[$i];
                $generalVoucher->save();

                if($findtaxSum > 0){
                    $generalVoucher = new GeneralVoucher();
                    $generalVoucher->voucher_id = $salePurchase->id;
                    if($findtaxSum > 0){
                        $generalVoucher->account_head_id = $PurchaseWarehouse->tax_account_id;
                    }else{
                        $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                    }
                    // $generalVoucher->account_head_id = $PurchaseWarehouse->purchase_account_id;
                    if($request->credit_to == "SUPPLIER"){
                        $generalVoucher->other_head_id = $salePurchase->party_id;
                    }
                    if($request->credit_to == "PURCHASER"){
                        $generalVoucher->other_head_id = $salePurchase->purchaser_id;
                    }
                    // $generalVoucher->other_head_id = $salePurchase->party_id;
                    $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                    $generalVoucher->product_id =  $request->product_id[$i];
                    $generalVoucher->date = $salePurchase->date;
                    $generalVoucher->voucher_no = $salePurchase->voucher_no;
                    $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                    $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                    $generalVoucher->stvalue = $request->sale_tax[$i];
                    $generalVoucher->quantity = $request->qty[$i];
                    $generalVoucher->v_type =  'PURCHASETAX';
                    $generalVoucher->narration = 'PURCHASETAX INVOICE';
                    // $generalVoucher->debit = $request->total[$i];
                    $generalVoucher->debit = $request->sale_tax[$i];
                    $generalVoucher->save();
                }

                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 if($request->credit_to == "SUPPLIER"){
                    $generalVoucher->account_head_id = $salePurchase->party_id;
                }
                if($request->credit_to == "PURCHASER"){
                    $generalVoucher->account_head_id = $salePurchase->purchaser_id;
                }
                //  $generalVoucher->account_head_id = $salePurchase->party_id;
                if($findtaxSum > 0){
                    $generalVoucher->other_head_id = $PurchaseWarehouse->purchasetax_account_id;
                }else{
                    $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
                }
                //  $generalVoucher->other_head_id = $PurchaseWarehouse->purchase_account_id;
                 $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                 $generalVoucher->product_id =  $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                 $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                 $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                 $generalVoucher->stvalue = $request->sale_tax[$i];
                 $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->v_type = 'PURCHASETAX';
                 $generalVoucher->narration = 'PURCHASETAX INVOICE';
                 $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->save();

            }
            $record=GodownStock::where('id',$request->grn_id)->where('type','GRN')->first();
            $record->update(['status' => 1]);
            Session::flash('flash_message', 'Purchase Tax Voucher Added Successfully!');
            return redirect('purchase-tax');
            // return redirect()->back()->with('flash_message', 'Purchase Tax Voucher Added Successfully!');
        }
        abort(500);
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE TAX VOUCHER')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


       $salepurchase = SalePurchase::whereType('PURCHASETAX')->where('id', $request->delete_voucher_no)->first();
        if ($salepurchase) {
            $record=GodownStock::where('id',$salepurchase->grn_dc_id)->where('type','GRN')->first();
            $record->update(['status' => 0]);
            SalePurchase::where('id', $request->delete_voucher_no)->delete();
            SalePurchaseDetail::whereType('PURCHASETAX')->where('sale_purchase_id', $request->delete_voucher_no)->delete();
            GeneralVoucher::where('v_type', 'PURCHASETAX')->where('voucher_id', $request->delete_voucher_no)->delete();
            Session::flash('flash_message', 'Purchase Tax Voucher Deleted Successfully!');
            return redirect('purchase-tax');
            // return redirect()->back()->with('flash_message', 'Purchase Tax Voucher Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
      
    }

    public function editData(Request $request)
    {
         $edit = SalePurchase::whereType('PURCHASETAX')->where('voucher_no', $request->voucher_no)->first();

        if ($edit) {
            $edit = SalePurchaseDetail::with('product:id,product_name,code,uom,tax')
                ->with(['salepurchase' => function ($query) {
                    $query->with(['grn'=>function($qry){
                        $qry->with(['inward_gatepass'=>function($r){
                            $r->with('supplier:id,party_name','purchaser:id,party_name');
                        }]);
                    }]);
                }])
                ->where('sale_purchase_id', $edit->id)
                ->whereType('PURCHASETAX')
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $purchase = SalePurchase::whereType('PURCHASETAX')->where('voucher_no', '<', $request->voucher_no)->max('voucher_no');
        if ($purchase) {
            $data = SalePurchaseDetail::with('product:id,product_name,code,uom,tax')
                ->with(['salepurchase' => function ($query) {
                    $query->with(['grn'=>function($qry){
                        $qry->with(['inward_gatepass'=>function($r){
                            $r->with('supplier:id,party_name','purchaser:id,party_name');
                        }]);
                    }]);
                }])
                ->where('voucher_no', $purchase)
                ->whereType('PURCHASETAX')
                ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $purchase = SalePurchase::whereType('PURCHASETAX')->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');

        if ($purchase) {
            $data = SalePurchaseDetail::with('product:id,product_name,code,uom,tax')
            ->with(['salepurchase' => function ($query) {
                $query->with(['grn'=>function($qry){
                    $qry->with(['inward_gatepass'=>function($r){
                        $r->with('supplier:id,party_name','purchaser:id,party_name');
                    }]);
                }]);
            }])
            ->where('voucher_no', $purchase)
            ->whereType('PURCHASETAX')
            ->get();
            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function report(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE TAX REPORT')
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
        File::cleanDirectory(base_path() .'/upload/purchase');
        $voucher_no = $request->voucher_no;
        $purchase = SalePurchase::where('type', 'PURCHASETAX')->where('voucher_no', $voucher_no)->first();
        if ($purchase) {
            $purchaseDetails = SalePurchaseDetail::with('product:id,product_name,code,uom,tax')
            ->with(['salepurchase' => function ($query) {
                $query->with(['grn'=>function($qry){
                    $qry->with(['inward_gatepass'=>function($r){
                        $r->with('supplier:id,party_name','purchaser:id,party_name','request_generate:id,date');
                    }]);
                }]);
            }])
            ->where('sale_purchase_id', $purchase->id)
            ->whereType('PURCHASETAX')
            ->get();

            $pdf = PDF::loadView('purchases.purchase-tax.invoice', compact('purchaseDetails'));
            $fileName =  'PurchaseTax' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/purchase/' . $fileName));
            return $fileName;
        } else{
            return false;
        }
    }
    public function LoadGrnRcord(Request $request)
    {
         $grn = GodownStock::whereStatus(0)->whereId($request->grn_id)->where('type','GRN')->first();
        if ($grn) {
            $GRNDetails = GodownStockDetail::with(['godownstock'=>function($qry){
                $qry->with(['inward_gatepass'=>function($query){
                    $query->with('supplier:id,party_name','purchaser:id,party_name');
                }]);
                
            }])
            ->with(['product'=>function($query) use ($grn){
                $query->with(['last_product_price'=>function($q) use ($grn){
                    $q->where('party_id',$grn->party_id)
                    ->orderBy('id','desc')
                    ->latest();
                }]);
            }])
            ->where('transaction_id', $grn->id)
            ->where('type','GRN')
            ->get();
            return Response::json(['data' => $GRNDetails]);
        } else {
            return Response::json(['data' => '']);
        }
    }
}
