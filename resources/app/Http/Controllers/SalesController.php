<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SalePurchase;
use App\Models\CustomerProduct;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\DeliveryChallan;
use App\Models\GodownStockDetail;
use App\Models\SalePurchaseDetail;
use App\Models\GeneralVoucher;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DeliveryChallanDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;
class SalesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'SALES INVOICE')
        ->where('right_name', 'ADD')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return "d";
        $codes = 1;
        $sales = SalePurchase::whereType('SALE')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->max('voucher_no');
        if ($sales) {
            $codes = $sales + 1;
        }

        // $DeliveryChallan = DeliveryChallan::where('type','DCNonGST')->whereStatus(0)->OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'id')->prepend('Select Challan', '');
        
      
        // $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
        //     ->select('*', DB::raw("CONCAT(parties.id,'_',parties.party_name,'_',parties.address) as id,party_name"))
        //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        //     ->where('parties.account_group_id', '1')
        //     ->Orwhere('parties.account_group_id', '7')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('parties.party_name', 'parties.id')
        //     ->prepend('Select Party Name', '');
        $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
            ->where('role', '=', 'Customer')
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

            $usertype = Auth::User()->role;
            if($usertype == "Admin"){
                $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
                $DeliveryChallan = DB::table('delivery_challans')
                ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
                ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
                ->where('delivery_challans.type', 'DCNonGST')
                ->where('delivery_challans.status', 0)
                ->orderBy('delivery_challans.id', 'asc')
                ->pluck('voucher_no','delivery_challans.id')
                ->prepend('Select Challan', '');
                $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->OrderBy('id', 'asc')
                ->pluck('product_name', 'id')
                ->prepend('Select Product', '');
            }else{
                $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
                $DeliveryChallan = DB::table('delivery_challans')
                ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
                ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
                ->where('delivery_challans.type', 'DCNonGST')
                ->where('delivery_challans.status', 0)
                ->where('delivery_challans.warehouse_id', Auth::User()->warehouse_id)
                ->orderBy('delivery_challans.id', 'asc')
                ->pluck('voucher_no','delivery_challans.id')
                ->prepend('Select Challan', '');
                $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->OrderBy('id', 'asc')
                ->pluck('product_name', 'id')
                ->prepend('Select Product', '');
            }
        return view('sales.index', compact('codes', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
    }

    public function Warehouse_voucherNo(Request $request){
        //  return $request;
        $codes = 1;
        $SalePurchase = SalePurchase::where('type','SALE')
        ->where('warehouse_id', $request->warehouseID)
        ->max('voucher_no');
        if ($SalePurchase) {
            $codes = $SalePurchase + 1;
        }
        
        return Response::json(['codes' => $codes]);
    }

    public function edit($SaleID){
        $data = SalePurchase::whereId($SaleID)->first();
                // $DeliveryChallan = DeliveryChallan::where('type','DCNonGST')->whereStatus(0)->OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'id')->prepend('Select Challan', '');
                $DeliveryChallan = DB::table('delivery_challans')
                ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
                ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
                ->where('delivery_challans.type', 'DCNonGST')
                ->where('delivery_challans.status', 0)
                ->orderBy('delivery_challans.id', 'asc')
                ->pluck('voucher_no','delivery_challans.id')
                ->prepend('Select Challan', '');
            $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->OrderBy('id', 'asc')
                ->pluck('product_name', 'id')
                ->prepend('Select Product', '');
            // $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            //     ->select('*', DB::raw("CONCAT(parties.id,'_',parties.party_name,'_',parties.address) as id,party_name"))
            //     ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            //     ->where('parties.account_group_id', '1')
            //     ->Orwhere('parties.account_group_id', '7')
            //     ->OrderBy('party_name', 'asc')
            //     ->pluck('parties.party_name', 'parties.id')
            //     ->prepend('Select Party Name', '');
            $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
                ->where('role', '=', 'Customer')
                // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
                ->OrderBy('party_name', 'asc')
                ->pluck('party_name', 'id')
                ->prepend('Select Party Name', '');
    
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
            return view('sales.index', compact('data', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
    }

    public function store(Request $request)
    {
        //  return $request;
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

        $SaleWarehouse = Warehouse::where('id', $request->warehouse_id)->first();
         
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALES INVOICE')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            
            $salePurchase = SalePurchase::where('id', $request->update_voucher_id)
            ->where('warehouse_id', $request->warehouse_id)
            ->where('type', 'SALE')->first();
            // $DC_PO = DeliveryChallan::where('id', $request->dcn_id)->first('po_no');
            $data = $request->all();
            $data['type'] = 'SALE';
            $data['updated_by'] = Auth::User()->id;
            $data['grn_dc_id'] = $request->dcn_id1;
            // $data['po_no'] = $DC_PO->po_no;
            $salePurchase->update($data);

            SalePurchaseDetail::whereType('SALE')->where('sale_purchase_id', $salePurchase->id)
            ->where('warehouse_id', $request->warehouse_id)
            ->delete();
            GeneralVoucher::where('v_type', 'SALE')->where('voucher_id', $salePurchase->id)
            ->where('warehouse_id', $request->warehouse_id)
            ->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'SALE';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id = $request->product_id[$i];
                $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->total[$i];
                // $salePurchaseDetails->st_rate = $request->st_rate[$i];
                // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->sale_qty = $request->sale_qty[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->po_no = $request->po_no[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;
                $salePurchaseDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id = $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                // $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->rate = $request->rate[$i];
                $generalVoucher->v_type =  'SALE';
                $generalVoucher->narration = 'SALE INVOICE';
                $generalVoucher->debit = $request->total[$i];
                $generalVoucher->save();
                 // Below account | Credit account | Cash paid account
                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                 $generalVoucher->other_head_id = $salePurchase->party_id;
                 $generalVoucher->warehouse_id =  $request->warehouse_id;
                 $generalVoucher->product_id = $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                //  $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->quantity = $request->sale_qty[$i];
                 $generalVoucher->rate = $request->rate[$i];
                 $generalVoucher->v_type = 'SALE';
                 $generalVoucher->narration = 'SALE INVOICE';
                 $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->save();
            }

            Session::flash('flash_message', 'Sale Voucher Updated Successfully!');
            return redirect('sales-voucher');
            return redirect()->back()->with('flash_message', 'Sale Voucher Updated Successfully!');
        } else {

            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALES INVOICE')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
           
            $sales = SalePurchase::whereType('SALE')
            ->where('warehouse_id', $request->warehouse_id)
            ->max('voucher_no');
            if ($sales) {
                $codes = $sales + 1;
            }
            // return $request;
            $DC_PO = DeliveryChallan::where('id', $request->dcn_id)->first('po_no');
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['type'] = 'SALE';
            $data['grn_dc_id'] = $request->dcn_id;
            $data['challan_type'] = $request->challantype;
            $data['po_no'] = $DC_PO->po_no;
            $salePurchase = SalePurchase::create($data);
            

            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'SALE';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id = $request->product_id[$i];
                $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->total[$i];
                // $salePurchaseDetails->st_rate = $request->st_rate[$i];
                // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
               $salePurchaseDetails->sale_qty = $request->sale_qty[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->po_no = $request->po_no[$i];
                 $salePurchaseDetails->created_by = Auth::User()->id;
                $salePurchaseDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id = $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                // $generalVoucher->quantity = $request->qty[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->rate = $request->rate[$i];
                $generalVoucher->v_type =  'SALE';
                $generalVoucher->narration = 'SALE INVOICE';
                $generalVoucher->debit = $request->total[$i];
                $generalVoucher->save();

                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                 $generalVoucher->other_head_id = $salePurchase->party_id;
                 $generalVoucher->warehouse_id =  $request->warehouse_id;
                 $generalVoucher->product_id = $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                //  $generalVoucher->quantity = $request->qty[$i];
                 $generalVoucher->quantity = $request->sale_qty[$i];
                 $generalVoucher->rate = $request->rate[$i];
                 $generalVoucher->v_type = 'SALE';
                 $generalVoucher->narration = 'SALE INVOICE';
                 $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->save();
            }
            // return $request->all();

            $record = DeliveryChallan::where('id', $request->dcn_id)->first();
            $record->update(['status' => 1]);
            Session::flash('flash_message', 'Sale Voucher Added Successfully!');
            return redirect('sales-voucher');
            return redirect()->back()->with('flash_message', 'Sale Voucher Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $editdata = SalePurchase::whereType('SALE')->where('voucher_no', $request->voucher_no)
        ->where('warehouse_id',  $request->warehouseID)
        ->first();
        $partyID = $editdata->party_id;
        $customerproduct = CustomerProduct::where('customer_id', $partyID)->count();
            if ($customerproduct > 0) {
                $status = 0;
                $edit = SalePurchaseDetail::with(['product' => function($query){
                    $query->with('customer_product');
                }])
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $editdata->id)
                    ->get();
                return Response::json(['data' => $edit, 'status' => $status]);
            } else {
                $status = 1;
                 $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                return Response::json(['data' => $edit, 'status' => $status]);
            }
    }

    public function LoadPreviousData(Request $request)
    {
        // return "d";
        // return $request;
        $prevoucher = SalePurchase::where('voucher_no', '<', $request->voucher_no)
        ->where('warehouse_id',  $request->warehouseID)
        ->whereType('SALE')->max('voucher_no');
        $sale = SalePurchase::where('voucher_no', $prevoucher)->whereType('SALE')
        ->where('warehouse_id',  $request->warehouseID)
        ->first();
        $partyID = $sale->party_id;
        $customerproduct = CustomerProduct::where('customer_id', $partyID)->count();
            if ($customerproduct > 0) {
                $status = 0;
                $edit = SalePurchaseDetail::with(['product' => function($query){
                    $query->with('customer_product');
                }])
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                return Response::json(['data' => $edit, 'status' => $status]);
            } else {
                $status = 1;
                 $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                return Response::json(['data' => $edit, 'status' => $status]);
            }

    }

    public function LoadNextData(Request $request)
    {
        $nextvoucher = SalePurchase::whereType('SALE')->where('voucher_no', '>', $request->voucher_no)
        ->where('warehouse_id',  $request->warehouseID)
        ->min('voucher_no');
        $sale = SalePurchase::where('voucher_no', $nextvoucher)->whereType('SALE')
        ->where('warehouse_id',  $request->warehouseID)
        ->first();
        $partyID = $sale->party_id;
        $customerproduct = CustomerProduct::where('customer_id', $partyID)->count();
            if ($customerproduct > 0) {
                $status = 0;
                $edit = SalePurchaseDetail::with(['product' => function($query){
                    $query->with('customer_product');
                }])
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                return Response::json(['data' => $edit, 'status' => $status]);
            } else {
                $status = 1;
                 $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                return Response::json(['data' => $edit, 'status' => $status]);
            }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALES INVOICE')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        // return $request;
        $voucherNo = $request->delete_voucher_no;
        $warehouseID = $request->delete_warehouse_id;
        $sale = SalePurchase::whereType('SALE')->where('voucher_no', $voucherNo)
        ->where('warehouse_id', $warehouseID)
        ->first();
        if ($sale) {
            
           $record = DeliveryChallan::where('id', $sale->grn_dc_id)->first();
           if($record){
            $record->update(['status' => 0]);
           }
            // SalePurchase::findOrFail($sale->id)->whereType('SALE')->delete();
            SalePurchase::where('id', $sale->id)->where('type', 'SALE')
            ->where('warehouse_id', $warehouseID)->delete();
            SalePurchaseDetail::whereType('SALE')->where('sale_purchase_id', $sale->id)
            ->where('warehouse_id', $warehouseID)->delete();
            GeneralVoucher::where('v_type', 'SALE')->where('voucher_id', $sale->id)
            ->where('warehouse_id', $warehouseID)->delete();
            // return "enter";
            Session::flash('flash_message', 'Sale Voucher Deleted Successfully!');
            return redirect('sales-voucher');
            // return redirect()->back()->with('flash_message', 'Sale Invoice Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }



    public function PrintVoucher(Request $request)
    {
    // return $request;
    $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    ->where('voucher_name', 'SALES INVOICE')
    ->where('right_name', 'PRINT')
    ->first();
    if (!$voucherRight) {
    return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        File::cleanDirectory(base_path() . '/upload/sales-voucher');
        $voucherNo = $request->voucher_no;
        $warehouseID = $request->warehouseID;
         $sale = SalePurchase::where('type', 'SALE')
        ->where('warehouse_id', $warehouseID)
        ->where('voucher_no', $voucherNo)->first();
        $partyID = $sale->party_id;
         $customerproduct = CustomerProduct::where('customer_id', $partyID)->count();
            if ($customerproduct > 0) {
                $status = 0;
                $salevoucherDetails = SalePurchaseDetail::with(['product' => function($query){
                    $query->with('customer_product');
                }])
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                // return Response::json(['data' => $edit, 'status' => $status]);
            } else {
                $status = 1;
                 $salevoucherDetails = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->where('warehouse_id',  $request->warehouseID)
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();
                // return Response::json(['data' => $edit, 'status' => $status]);
            }
                $pdf = PDF::loadView('sales.invoice', compact('salevoucherDetails', 'status'));
            $fileName =  'sales-voucher' . $voucherNo . '.pdf';
            $pdf->save(base_path('upload/sales-voucher/' . $fileName));
            return $fileName;
    }
    public function getdcRecord(Request $request)
    {
        // return $request;
        $challan_id = $request->dcn_id;
         $dcChallan = DeliveryChallan::where('id', $challan_id)->first();
          $partyID = $dcChallan->party_id;
          $customerproduct = CustomerProduct::where('customer_id', $partyID)->count();
        if ($customerproduct > 0) {
            $status = 0;
            $data = DeliveryChallanDetails::with(['product' => function($query){
                $query->with('customer_product');
            }])
                ->with('party:id,party_name,address')
                ->where('challan_id', $dcChallan->id)
                // ->where('customer_product_id', '!=', null)
                ->with('delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
                ->get();
            return Response::json(['data' => $data, 'status' => $status]);
        } else {
            $status = 1;
            $data = DeliveryChallanDetails::with('product:id,code,product_name,uom,packing')
                ->with('party:id,party_name,address')
                ->where('challan_id', $dcChallan->id)
                // ->where('customer_product_id', '!=', null)
                ->with('delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
                ->get();
            return Response::json(['data' => $data, 'status' => $status]);
        }
    }
}
