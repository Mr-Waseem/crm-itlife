<?php

namespace App\Http\Controllers;
use App\Models\Party;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Warehouse;
use App\Models\GodownStock;
use App\Models\CustomerProduct;
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

class DirectPurchaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
         $sales = SalePurchase::whereType('DIRECT PURCHASE')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->max('voucher_no');
        if ($sales) {
            $codes = (int)$sales + 1;
        }
        
        // $DeliveryChallan = DeliveryChallan::where('type','DCNonGST')->whereStatus(0)->OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'id')->prepend('Select Challan', '');
        $DeliveryChallan = DB::table('delivery_challans')
            ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
            ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
            ->where('delivery_challans.type', 'DCNonGST')
            ->where('delivery_challans.status', 0)
            ->orderBy('delivery_challans.id', 'asc')
            ->pluck('voucher_no','delivery_challans.id')
            ->prepend('Select Challan', '');
        // $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
        // $products = Product::select(DB::raw('CONCAT(`id`, "_", `uom`, "_", `packing`, "_", `tax`,"_", `product_name`,"_", `product_code`) AS `id`,`product_name`'))
        //     ->where('warehouse_id', Auth::User()->warehouse_id)
        //     ->OrderBy('id', 'asc')
        //     ->pluck('product_name', 'id')
        //     ->prepend('Select Product', '');

         $products = Product::select(DB::raw(
            'CONCAT(`id`, "_", `uom`, "_", `packing`, "_", `tax`,"_", `product_name`,"_", `code`) AS `id`,
            CONCAT(`code`, "-", `product_name`) AS `product_name`'
            ))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Products', '');
        $customers = Party::select(DB::raw(
            'CONCAT(`id`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->where('role', '=', 'Supplier')
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

        // $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
            ->pluck('name', 'id');
        }

        $purchasers = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->whereRole('Purchaser')
            ->OrderBy('id', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Purchaser', '');

        $invoiceType = ['' => 'Select Any', 'INVOICE' => 'INVOICE', 'WITHOUT INVOICE' => 'WITHOUT INVOICE'];
        return view('purchases.direct.index', compact('codes', 'customers', 'products', 'DeliveryChallan', 'warehouse','invoiceType', 'purchasers'));
    }

    public function Warehouse_voucherNo(Request $request){
        // return $request;
        $codes = 1;
         $code = SalePurchase::where('type', 'DIRECT PURCHASE')
        ->where('warehouse_id', $request->warehouseID)
        ->max('voucher_no');
        if ($code) {
            $codes = (int)$code + 1;
        }
        return Response::json(['codes' => $codes]);
    }

    public function getProduct(Request $request){
        // return $request;
        $ProductID = $request->productID;
         $VoucherDate= $request->VoucherDate;
         $partyID= $request->partyID;
         
           $customerproduct = CustomerProduct::with('product:id,packing,tax,uom')
             ->where('customer_id', $request->partyID)->count();
 
         if ($customerproduct > 0) {
            // return "df";
             $company = false;
               $productdata = CustomerProduct::where('id', $ProductID)->first();
             $GetProductID = $productdata['product_id'];
 
             $products = CustomerProduct::
             with(['product' => function($query) use ($ProductID, $VoucherDate, $GetProductID){
                  $query->with(['productRates' => function($query) use ($ProductID, $VoucherDate, $GetProductID){
                 $query->where('product_id', $GetProductID);
                 $query->whereDate('voucher_date', '<', $VoucherDate);
                 $query->orderby('voucher_date', 'desc')->first();
                  }]);
            }])
            ->where('customer_id', $request->partyID)
            ->where('id', $request->productID)
            ->get();
         } else {
             $company = true;
            //  return "d";
             $products = Product::with(['productRates' => function($query) use ($ProductID, $VoucherDate){
                 // $query->orderby('id', 'desc')->first();
                 $query->where('product_id', $ProductID);
                 $query->whereDate('voucher_date', '<', $VoucherDate);
                 $query->orderby('voucher_date', 'desc')->first();
             }])
             ->where('id', $request->productID)->first();
         }
         return json_encode(['products' => $products, 'company' => $company]);
     }


    public function edit($SaleID){
        $data = SalePurchase::whereId($SaleID)->first();

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
    $customers = Party::select(DB::raw(
        'CONCAT(`id`) AS `id`,
        CONCAT(`code`, "-", `party_name`) AS `party_name`'
        ))
        ->where('role', '=', 'Customer')
        // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
        ->OrderBy('party_name', 'asc')
        ->pluck('party_name', 'id')
        ->prepend('Select Party Name', '');

    // $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');

    $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
            ->pluck('name', 'id');
        }

    $invoiceType = ['' => 'Select Any', 'INVOICE' => 'INVOICE', 'WITHOUT INVOICE' => 'WITHOUT INVOICE'];
    return view('sales-return.index', compact('data', 'customers', 'products', 'DeliveryChallan', 'warehouse','invoiceType'));

    }

    public function store(Request $request)
    {
        
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

        // return$SaleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->first();
         $SaleWarehouse = Warehouse::where('id', $request->warehouse_id)->first();
         
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT PURCHASE')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
                $salePurchase = SalePurchase::where('id', $request->update_voucher_id)
                // ->where('warehouse_id', Auth::User()->warehouse_id)
                ->where('type', 'DIRECT PURCHASE')->first();
            
                // return $request;
            $data = $request->all();
            $data['warehouse_id'] = $request->warehouse_id;
            $data['type'] = 'DIRECT PURCHASE';
            $data['updated_by'] = Auth::User()->id;
            // $data['grn_dc_id'] = $request->dcn_id1;
            // $data['grn_dc_id'] = $request->dcn_id1;
            // return $data;
            $salePurchase->update($data);
            // return $request;
            // SalePurchaseDetail::whereType('SALE')->where('sale_purchase_id', $salePurchase->id)->delete();
            // GeneralVoucher::where('v_type', 'SALE')->where('voucher_id', $salePurchase->id)->delete();

            // SalePurchase::where('id', $sale->id)->where('type', 'SALE RETURN')->delete();
            SalePurchaseDetail::whereType('DIRECT PURCHASE')->where('sale_purchase_id', $salePurchase->id)->delete();
            GeneralVoucher::where('v_type', 'DIRECT PURCHASE')->where('voucher_id', $salePurchase->id)->delete();
            GodownStockDetail::where('type', 'DIRECT PURCHASE')->where('transaction_id', $salePurchase->id)->delete();
// return ":D";
             $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                if($request->qty[$i] > 0){
                    $salePurchaseDetails = new SalePurchaseDetail();
                    $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                    $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                    $salePurchaseDetails->date = $salePurchase->date;
                    $salePurchaseDetails->type = 'DIRECT PURCHASE';
                    $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                    $salePurchaseDetails->party_id = $salePurchase->party_id;
                    $salePurchaseDetails->product_id = $request->product_id[$i];
                    $salePurchaseDetails->demandQty = 0;
                    $salePurchaseDetails->qty = $request->pack[$i];
                    $salePurchaseDetails->rate = $request->rate[$i];
                    // $salePurchaseDetails->excl_val = $request->excl_val[$i];
                    // $salePurchaseDetails->st_rate = $request->st_rate[$i];
                    // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                    // $salePurchaseDetails->demandQty = $request->sale_qty[$i];
                    $salePurchaseDetails->sale_qty = $request->qty[$i];
                    // $salePurchaseDetails->sale_qty = $request->sale_qty[$i];
                    $salePurchaseDetails->total = $request->total[$i];
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
                    $generalVoucher->quantity = $request->qty[$i];
                    $generalVoucher->rate = $request->rate[$i];
                    $generalVoucher->v_type =  'DIRECT PURCHASE';
                    $generalVoucher->narration = 'DIRECT PURCHASE';
                    // $generalVoucher->debit = $request->total[$i];
                    $generalVoucher->credit = $request->total[$i];
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
                     $generalVoucher->quantity = $request->qty[$i];
                     $generalVoucher->rate = $request->rate[$i];
                     $generalVoucher->v_type = 'DIRECT PURCHASE';
                     $generalVoucher->narration = 'DIRECT PURCHASE';
                    //  $generalVoucher->credit = $request->total[$i];
                     $generalVoucher->debit = $request->total[$i];
                     $generalVoucher->save();
    
                     $godownstockDetails = new GodownStockDetail();
                    $godownstockDetails->voucher_no = $salePurchase->voucher_no;
                    $godownstockDetails->transaction_id = $salePurchase->id;
                    // $godownstockDetails->inward_gatepass_id =1;
                    $godownstockDetails->date = $salePurchase->date;
                    $godownstockDetails->type = 'DIRECT PURCHASE';
                    $godownstockDetails->warehouse_id = $request->warehouse_id;
                    $godownstockDetails->party_id = $salePurchase->party_id;
                    // $customerProduct = CustomerProduct::where('product_id', $request->product_id[$i])->first();
                    // if($customerProduct){
                    //     $godownstockDetails->product_id = $customerProduct->product_id;
                    // }else{
                    $godownstockDetails->product_id = $request->product_id[$i];
                    // }
                    // $godownstockDetails->warehouse_id = $request->warehouse_id;
                    $godownstockDetails->qty_in = $request->qty[$i];
                    // $godownstockDetails->qty_out = $request->qty[$i];
                    $godownstockDetails->qty_out = 0;
                    $godownstockDetails->demand_qty = 0;
                    $godownstockDetails->rate = $request->rate[$i];
                    $godownstockDetails->remarks = $salePurchase->remarks;;
                    $godownstockDetails->created_by = Auth::User()->id;
                    $godownstockDetails->sale_rate = 0;
                    $godownstockDetails->save();
                }
            }
            Session::flash('flash_message', 'Purchase Voucher Updated Successfully!');
            return redirect('direct-purchases');
            // return redirect()->back()->with('flash_message', 'Sale Voucher Updated Successfully!');
        } else {
            // return $request;
             $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT PURCHASE')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
           
             $sales = SalePurchase::whereType('DIRECT PURCHASE')
            ->where('warehouse_id', $request->warehouse_id)
            ->max('voucher_no');
            if ($sales) {
                $codes = (int)$sales + 1;
            }
            $data = $request->all();
            $data['warehouse_id'] = $request->warehouse_id;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['sale_return_invoice_no'] = $request->invoice_no;
            $data['type'] = 'DIRECT PURCHASE';
            // $data['grn_dc_id'] = $request->dcn_id1;
            // $data['challan_type'] = $request->challantype;
            $salePurchase = SalePurchase::create($data);
            // return "dobe";
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                if($request->qty[$i] > 0){
                    $salePurchaseDetails = new SalePurchaseDetail();
                    $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                    $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                    $salePurchaseDetails->date = $salePurchase->date;
                    $salePurchaseDetails->type = 'DIRECT PURCHASE';
                    $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                    $salePurchaseDetails->party_id = $salePurchase->party_id;
                    $salePurchaseDetails->product_id = $request->product_id[$i];
                    $salePurchaseDetails->demandQty = 0;
                    $salePurchaseDetails->qty = $request->pack[$i];
                    $salePurchaseDetails->rate = $request->rate[$i];
                    // $salePurchaseDetails->excl_val = $request->excl_val[$i];
                    // $salePurchaseDetails->st_rate = $request->st_rate[$i];
                    // $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                    // $salePurchaseDetails->demandQty = $request->sale_qty[$i];
                    $salePurchaseDetails->sale_qty = $request->qty[$i];
                    // $salePurchaseDetails->sale_qty = $request->sale_qty[$i];
                    $salePurchaseDetails->total = $request->total[$i];
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
                    $generalVoucher->quantity = $request->qty[$i];
                    $generalVoucher->rate = $request->rate[$i];
                    $generalVoucher->v_type =  'DIRECT PURCHASE';
                    $generalVoucher->narration = 'DIRECT PURCHASE';
                    // $generalVoucher->debit = $request->total[$i];
                    $generalVoucher->credit = $request->total[$i];
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
                     $generalVoucher->quantity = $request->qty[$i];
                     $generalVoucher->rate = $request->rate[$i];
                     $generalVoucher->v_type = 'DIRECT PURCHASE';
                     $generalVoucher->narration = 'DIRECT PURCHASE';
                    //  $generalVoucher->credit = $request->total[$i];
                     $generalVoucher->debit = $request->total[$i];
                     $generalVoucher->save();
    
                     $godownstockDetails = new GodownStockDetail();
                    $godownstockDetails->voucher_no = $salePurchase->voucher_no;
                    $godownstockDetails->transaction_id = $salePurchase->id;
                    // $godownstockDetails->inward_gatepass_id =1;
                    $godownstockDetails->date = $salePurchase->date;
                    $godownstockDetails->type = 'DIRECT PURCHASE';
                    $godownstockDetails->warehouse_id = $request->warehouse_id;
                    $godownstockDetails->party_id = $salePurchase->party_id;
                    // $customerProduct = CustomerProduct::where('product_id', $request->product_id[$i])->first();
                    // if($customerProduct){
                    //     $godownstockDetails->product_id = $customerProduct->product_id;
                    // }else{
                    $godownstockDetails->product_id = $request->product_id[$i];
                    // }
                    // $godownstockDetails->warehouse_id = $request->warehouse_id;
                    $godownstockDetails->qty_in = $request->qty[$i];
                    // $godownstockDetails->qty_out = $request->qty[$i];
                    $godownstockDetails->qty_out = 0;
                    $godownstockDetails->demand_qty = 0;
                    $godownstockDetails->rate = $request->rate[$i];
                    $godownstockDetails->remarks = $salePurchase->remarks;;
                    $godownstockDetails->created_by = Auth::User()->id;
                    $godownstockDetails->sale_rate = 0;
                    $godownstockDetails->save();
                }
               
            }
            // return $request->all();

            // $record = DeliveryChallan::where('id', $request->dcn_id)->first();
            // $record->update(['status' => 1]);
            Session::flash('flash_message', 'Purchase Voucher Added Successfully!');
            return redirect('direct-purchases');
            return redirect()->back()->with('flash_message', 'Sale Return Added Successfully!');
        }
    }

    public function LoadInvoices(Request $request)
    {
        // return $request;
        $editdata = SalePurchase::whereType('SALE')->where('voucher_no', $request->invoice_no)->first();
        if ($editdata) {
            if ($editdata->challan_type == 'CDC') {
                $edit = SalePurchaseDetail::with(['cusproduct' => function ($re) {
                    $re->with('product:id,product_name,uom,tax,code,packing');
                }])
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $editdata->id)
                    ->get();
                return Response::json(['data' => $edit]);
            } else {
                $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with(['salepurchase' => function ($qry) {
                        $qry->with('dc');
                    }])
                    ->with('party:id,party_name,address')
                    ->whereType('SALE')
                    ->where('sale_purchase_id', $editdata->id)
                    ->get();

                return Response::json(['data' => $edit]);
            }
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function editData(Request $request)
    {
        $warehouseID = $request->warehouseID;
        $VoucherNO = $request->voucher_no;
        $Purchasedata = SalePurchase::where('type', 'DIRECT PURCHASE')
        ->where('warehouse_id', $warehouseID)
        ->where('voucher_no', $VoucherNO)->first();
        if ($Purchasedata) {
            $purchase = SalePurchase::with(['sale_purchase_details' => function ($qry) {
                $qry->with('product:id,product_name,uom,tax,code,packing');
            }])
            // ->with('party:id,party_name')
            // ->with('purchaser:id,party_name')
            ->with('warehouse:id,name')
                // ->with('user:id,name')
                
                ->where('voucher_no', $Purchasedata->voucher_no)
                ->where('type', 'DIRECT PURCHASE')
                ->where('warehouse_id', $warehouseID)
                // ->orderBy('id', 'asc')
                ->first();
                return Response::json(['data' => $purchase]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        //  return $request;
        $warehouseID = $request->warehouseID;
        $VoucherNO = $request->voucher_no;
        $PurchaseNo = SalePurchase::where('warehouse_id', '=', $warehouseID)
            ->where('voucher_no', '<', $VoucherNO)
            ->where('type', 'DIRECT PURCHASE')
            ->max('voucher_no');
        if ($PurchaseNo) {
             $purchase = SalePurchase::with(['sale_purchase_details' => function ($qry) {
                $qry->with('product:id,product_name,uom,tax,code,packing');
            }])
            // ->with('party:id,party_name')
            // ->with('purchaser:id,party_name')
            ->with('warehouse:id,name')
                // ->with('user:id,name')
                
                ->where('voucher_no', $PurchaseNo)
                ->where('type', 'DIRECT PURCHASE')
                ->where('warehouse_id', $warehouseID)
                // ->orderBy('id', 'asc')
                ->first();
                return Response::json(['data' => $purchase]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $warehouseID = $request->warehouseID;
        $VoucherNO = $request->voucher_no;
         $PurchaseNo = SalePurchase::where('warehouse_id', '=', $warehouseID)
            ->where('voucher_no', '>', $VoucherNO)
            ->where('type', 'DIRECT PURCHASE')
            ->min('voucher_no');

            // $PurchaseNo = SalePurchase::where('warehouse_id', '=', $warehouseID)
            // ->where('voucher_no', '<', $VoucherNO)
            // ->where('type', 'DIRECT PURCHASE')
            // ->max('voucher_no');
            if($PurchaseNo) {
                 $purchase = SalePurchase::with(['sale_purchase_details' => function ($qry) {
                   $qry->with('product:id,product_name,uom,tax,code,packing');
               }])
               // ->with('party:id,party_name')
               // ->with('purchaser:id,party_name')
               ->with('warehouse:id,name')
                   // ->with('user:id,name')
                ->where('voucher_no', $PurchaseNo)
                ->where('type', 'DIRECT PURCHASE')
                ->where('warehouse_id', $warehouseID)
                // ->orderBy('id', 'asc')
                ->first();
                   return Response::json(['data' => $purchase]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        // return $request;
        $VoucherNo = $request->delete_voucher_no;
        $warehouseID = $request->delete_warehouse_id;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DIRECT PURCHASE')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
         $Purchase = SalePurchase::whereType('DIRECT PURCHASE')
        ->where('warehouse_id', $warehouseID)
        ->where('voucher_no', $VoucherNo)->first();
        if ($Purchase) {
        //    $record = DeliveryChallan::where('id', $sale->grn_dc_id)->first();
        //    if($record){
        //     $record->update(['status' => 0]);
        //    }
            // SalePurchase::where('id', $sale->id)->where('type', 'SALE RETURN')->delete();
            // SalePurchaseDetail::whereType('SALE RETURN')->where('sale_purchase_id', $sale->id)->delete();
            // GeneralVoucher::where('v_type', 'SALE RETURN')->where('voucher_id', $sale->id)->delete();
            // GodownStockDetail::where('type', 'SALE RETURN')->where('transaction_id', $sale->id)->delete();
            SalePurchase::where('id', $Purchase->id)->where('type', 'DIRECT PURCHASE')->delete();
            SalePurchaseDetail::whereType('DIRECT PURCHASE')->where('sale_purchase_id', $Purchase->id)->delete();
            GeneralVoucher::where('v_type', 'DIRECT PURCHASE')->where('voucher_id', $Purchase->id)->delete();
            GodownStockDetail::where('type', 'DIRECT PURCHASE')->where('transaction_id', $Purchase->id)->delete();

            Session::flash('flash_message', 'Purchase Voucher Deleted Successfully!');
            return redirect('direct-purchases');
            return redirect()->back()->with('flash_message', 'Sale Return Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function report(Request $request)
    {
        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $party_id = $request->party_id;
            $reportType = $request->report_type;
            if ($reportType == 'summary') {
                $summaryReport = SalePurchaseDetail::join('products', 'products.id', '=', 'sale_purchase_details.product_id')
                    ->join('parties', 'parties.id', '=', 'sale_purchase_details.party_id')
                    ->select(
                        'parties.party_name',
                        DB::raw('SUM(sale_purchase_details.qty) as qty'),
                        DB::raw('SUM(sale_purchase_details.total) as total'),
                        'sale_purchase_details.date',
                        'sale_purchase_details.voucher_no',
                    )
                    ->where('sale_purchase_details.party_id', $party_id)
                    ->whereDate('sale_purchase_details.date', '>=', $fromDate)
                    ->whereDate('sale_purchase_details.date', '<=', $toDate)
                    ->groupBy('parties.party_name')
                    ->orderBy('parties.id')
                    ->get();
                return response()->json(['data' => $summaryReport]);
            }
            if ($reportType == 'detail') {
                $detailReport = SalePurchaseDetail::with('party:id,party_name', 'product:id,product_name,code,uom')
                    ->where('party_id', $party_id)
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->get();
                return response()->json(['data' => $detailReport]);
            }
        }
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->where('parties.account_group_id', '1')
            ->Orwhere('parties.account_group_id', '7')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');

        return view('sales-return.report', compact('customers'));
    }

    public function PrintVoucher(Request $request)
    {
        //  return $request;
        File::cleanDirectory(base_path() . '/upload/purchase');
        $voucher_no = $request->voucher_no;
        $warehouseID = $request->warehouseID;
         $salevoucher = SalePurchase::where('type', 'DIRECT PURCHASE')
            ->where('warehouse_id', $warehouseID)
            ->where('voucher_no', $voucher_no)->first();
        if ($salevoucher) {
                 $salevoucher = SalePurchase::with(['sale_purchase_details' => function ($qry) {
                    $qry->with('product:id,product_name,uom,tax,code,packing');
                }])
                ->with('party:id,party_name')
                ->with('purchaser:id,party_name')
                ->with('warehouse:id,name')
                ->with('user:id,name')
                ->where('id', $salevoucher->id)
                // ->where('type', 'DIRECT PURCHASE')
                // ->where('warehouse_id', $warehouseID)
                // ->orderBy('id', 'asc')
                ->first();
            $pdf = PDF::loadView('purchases.direct.invoice', compact('salevoucher'));
            $fileName =  'Purchase-' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/purchase/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }


    public function getdcRecord(Request $request)
    {
        $challan_id = $request->dcn_id;
        $dcChallan = DeliveryChallan::where('id', $challan_id)->first();

        if ($dcChallan->type == 'CDC') {
            $data = DeliveryChallanDetails::with(['cusproduct' => function ($query) {
                $query->with('product');
            }])
                ->with('party:id,party_name,address')
                ->where('challan_id', $challan_id)
                ->where('customer_product_id', '!=', null)
                ->with('delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
                ->get();
            return Response::json(['data' => $data]);
        } else {
            // return "e";
             $data = GodownStockDetail::with('product:id,product_name,uom,tax,code,packing', 'delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
                ->with('party:id,party_name,address')
                ->where('transaction_id', $dcChallan->id)
                ->where('type', 'DCNonGST')
                ->get();
            return Response::json(['data' => $data]);
        }
    }
}
