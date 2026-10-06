<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Setting;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SalePurchase;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\DeliveryChallan;
use App\Models\GodownStockDetail;
use App\Models\SalePurchaseDetail;
use App\Models\CustomerProduct;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DeliveryChallanDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;
class DirectSalesTaxInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return "dd";
      
        // Start global voucher series at 1001 if no higher vouchers exist
        $maxVoucher = SalePurchase::whereType('DIRECT SALESTAX INVOICE')->max('voucher_no');
        if ($maxVoucher && intval($maxVoucher) >= 1000) {
            $codes = intval($maxVoucher) + 1;
        } else {
            $codes = 1001;
        }

        // $DeliveryChallan = DeliveryChallan::where('type','DC')->whereStatus(0)->OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'id')->prepend('Select Challan', '');
        $DeliveryChallan = DB::table('delivery_challans')
            ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
            ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
            ->where('delivery_challans.type', 'DC')
            ->where('delivery_challans.status', 0)->orderBy('delivery_challans.id', 'asc')
            ->pluck('voucher_no','delivery_challans.id')
            ->prepend('Select Challans', '');
            // $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`,"_", `packing`,"_", `tax`) AS `id`,`product_name`'))
            $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `packing`,"_", `tax`) AS `id`,`product_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
            ->where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

        // $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        $usertype = Auth::User()->role;
        if($usertype == "Admin"){
            $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
     
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        }
        return view('saletax-invoice.direct.index', compact('codes', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
    }

    public function Warehouse_voucherNo(Request $request){
        //  return $request;
        // Use global series (ignore warehouse) so voucher numbers continue across warehouses
        // Ensure new series starts at 1001 if max is lower
        $maxVoucher = SalePurchase::where('type','DIRECT SALESTAX INVOICE')->max('voucher_no');
        if ($maxVoucher && intval($maxVoucher) >= 1000) {
            $codes = intval($maxVoucher) + 1;
        } else {
            $codes = 1001;
        }
        
        return Response::json(['codes' => $codes]);
    }

    public function edit($SaleID){
        $data = SalePurchase::whereId($SaleID)->first();
        $DeliveryChallan = DB::table('delivery_challans')
            ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
            ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
            ->where('delivery_challans.type', 'DC')
            ->where('delivery_challans.status', 0)->orderBy('delivery_challans.id', 'asc')
            ->pluck('voucher_no','delivery_challans.id')
            ->prepend('Select Challans', '');
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`) AS `id`,`product_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
            ->where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

            $usertype = Auth::User()->role;
            if($usertype == "Admin"){
                $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('Select Warehouse', '');
         
            }else{
                $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
            }
        return view('saletax-invoice.direct.index', compact('data', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
    }

    public function store(Request $request)
    {
    //    return $request->all();
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
                ->where('voucher_name', 'DIRECT SALESTAX INVOICE')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // Find sale purchase by id only (series is global, do not restrict by warehouse)
            $salePurchase = SalePurchase::where('id', $request->update_voucher_id)
            ->where('type', 'DIRECT SALESTAX INVOICE')->first();
            // return $request->all();
            $data = $request->all();
            $data['type'] = 'DIRECT SALESTAX INVOICE';
            $data['warehouse_id'] = $request->warehouse_id;
            $data['updated_by'] = Auth::User()->id;
            $data['grn_dc_id'] = $request->dcn_id1;
            $salePurchase->update($data);
            // return "enter";
            // Delete existing details & vouchers by sale_purchase id (no warehouse filter)
            SalePurchaseDetail::whereType('DIRECT SALESTAX INVOICE')
            ->where('sale_purchase_id', $salePurchase->id)->delete();
            GeneralVoucher::where('v_type', 'DIRECT SALESTAX INVOICE')
            ->where('voucher_id', $salePurchase->id)->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'DIRECT SALESTAX INVOICE';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id = $request->product_id[$i];
                // $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->sale_qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->excl_val[$i];
                $salePurchaseDetails->st_rate = $request->st_rate[$i];
                $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->sale_qty = $request->sale_qty[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;
                $salePurchaseDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i] == null ? 0 : $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type =  'DIRECT SALESTAX INVOICE';
                $generalVoucher->narration = $request->remarks;
                $generalVoucher->debit = $request->total[$i];
                $generalVoucher->save();
                
                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                 $generalVoucher->other_head_id = $salePurchase->party_id;
                 $generalVoucher->warehouse_id =  $request->warehouse_id;
                 $generalVoucher->product_id =  $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                 $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                 $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                 $generalVoucher->stvalue = $request->sale_tax[$i] == null ? 0 : $request->sale_tax[$i];
                 $generalVoucher->quantity = $request->sale_qty[$i];
                 $generalVoucher->v_type = 'DIRECT SALESTAX INVOICE';
                 $generalVoucher->narration = $request->remarks;
                //  $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->credit = $request->excl_val[$i];
                 $generalVoucher->save();

                 $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $SaleWarehouse->tax_account_id;
                $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type = 'DIRECT SALESTAX INVOICE';
                $generalVoucher->narration = $request->remarks;
                // $generalVoucher->credit = $request->total[$i];
                // $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->credit = $request->sale_tax[$i];
                $generalVoucher->save();
            }
            // return "submit";


            File::cleanDirectory(base_path() . '/upload/salestax-invoice');
            // $voucher_no = $salePurchase->voucher_no;
            //   $salevoucher = SalePurchase::where('type', 'DIRECT SALESTAX INVOICE')->where('voucher_no', $voucher_no)->first();
            
                                     $salevoucherDetails = SalePurchaseDetail::with(['salepurchase' => function ($qry) {
                                         $qry->with('party');
                                         }])
                                         ->with('product:id,product_name,uom,tax,code,packing')
                                         ->where('sale_purchase_id', $salePurchase->id)
                                         ->where('type', 'DIRECT SALESTAX INVOICE')
                                         ->orderBy('id', 'asc')
                                         ->get();

                                         $warehouseTitle = Setting::first();
                                         // Use the warehouse associated with the saved sale purchase
                                         $warehouse = Warehouse::where('id', $salePurchase->warehouse_id)->first();
                                         $pdf = PDF::loadView('saletax-invoice.direct.invoice', compact('salevoucherDetails', 'warehouse', 'warehouseTitle'));
                     return $pdf->stream('invoice.pdf');
                     $fileName =  'Invoice.pdf';
                     $pdf->save(base_path('upload/salestax-invoice/' . $fileName));
                     return $fileName;


            Session::flash('flash_message', 'Direct SaleTax Voucher Updated Successfully!');
            return redirect('direct-saletax-invoice');
            // return redirect()->back()->with('flash_message', 'SaleTax Voucher Updated Successfully!');
        } else {
            // return $request->all();
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT SALESTAX INVOICE')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // Use global series for voucher numbering (do not limit by warehouse)
            // Ensure new series starts at 1001 if max is lower
            $maxVoucher = SalePurchase::whereType('DIRECT SALESTAX INVOICE')->max('voucher_no');
            if ($maxVoucher && intval($maxVoucher) >= 1000) {
                $codes = intval($maxVoucher) + 1;
            } else {
                $codes = 1001;
            }

            $data = $request->all();
            $data['warehouse_id'] = $request->warehouse_id;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['type'] = 'DIRECT SALESTAX INVOICE';
            // $data['grn_dc_id'] = $request->dcn_id;
            $data['challan_type'] = 'DIRECT SALESTAX INVOICE';
            $salePurchase = SalePurchase::create($data);

            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'DIRECT SALESTAX INVOICE';
                $salePurchaseDetails->warehouse_id = $request->warehouse_id;
                $salePurchaseDetails->party_id = $salePurchase->party_id;
                $salePurchaseDetails->product_id = $request->product_id[$i];
               
                // $salePurchaseDetails->demandQty = $request->demandQty[$i];
                $salePurchaseDetails->qty = $request->sale_qty[$i];
                $salePurchaseDetails->rate = $request->rate[$i];
                $salePurchaseDetails->excl_val = $request->excl_val[$i];
                $salePurchaseDetails->st_rate = $request->st_rate[$i];
                $salePurchaseDetails->sale_tax = $request->sale_tax[$i];
                $salePurchaseDetails->sale_qty = $request->sale_qty[$i];
                $salePurchaseDetails->total = $request->total[$i];
                $salePurchaseDetails->created_by = Auth::User()->id;
                $salePurchaseDetails->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $salePurchase->party_id;
                $generalVoucher->other_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type =  'DIRECT SALESTAX INVOICE';
                $generalVoucher->narration = $request->remarks;
                $generalVoucher->debit = $request->total[$i];
                // $generalVoucher->debit = $request->excl_val[$i];
                $generalVoucher->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
               $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type = 'DIRECT SALESTAX INVOICE';
                $generalVoucher->narration = $request->remarks;
                // $generalVoucher->credit = $request->total[$i];
                $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->tax_account_id;
                $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  $request->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type = 'DIRECT SALESTAX INVOICE';
                $generalVoucher->narration = $request->remarks;
                // $generalVoucher->credit = $request->total[$i];
                // $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->credit = $request->sale_tax[$i];
                $generalVoucher->save();
            }

            $salevoucherDetails = SalePurchaseDetail::with(['salepurchase' => function ($qry) {
                $qry->with('party');
                }])
                ->with('product:id,product_name,uom,tax,code,packing')
                ->where('sale_purchase_id', $salePurchase->id)
                ->where('type', 'DIRECT SALESTAX INVOICE')
                ->orderBy('id', 'asc')
                ->get();

                $warehouseTitle = Setting::first();
                // Use the warehouse associated with the saved sale purchase
                $warehouse = Warehouse::where('id', $salePurchase->warehouse_id)->first();
                $pdf = PDF::loadView('saletax-invoice.direct.invoice', compact('salevoucherDetails', 'warehouse', 'warehouseTitle'));
                return $pdf->stream('invoice.pdf');
            // return "sub";
            // $record = DeliveryChallan::where('id', $request->dcn_id)->first();
            // $record->update(['status' => 1]);
            Session::flash('flash_message', 'Saletax Invoice Created Successfully!');
            return redirect('direct-saletax-invoice');
            return redirect()->back()->with('flash_message', 'Direct Saletax Voucher Added Successfully!');
        }
    }

    public function printInvoice()
    {
        $pdf = PDF::loadView('saletax-invoice.direct.invoice', compact('salevoucherDetails'));

        // Save the file temporarily in the storage folder
        $filePath = storage_path('app/public/invoice.pdf');
        $pdf->save($filePath);

        return response()->json(['pdf_url' => asset('storage/invoice.pdf')]);
    }

    public function editData(Request $request)
    {
        // Find sale by voucher number globally (series is not warehouse-specific)
        $sale = SalePurchase::whereType('DIRECT SALESTAX INVOICE')
        ->where('voucher_no', $request->voucher_no)->first();
        $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
        ->with('salepurchase')
            ->with('party:id,party_name,address')
            ->whereType('DIRECT SALESTAX INVOICE')
            ->where('sale_purchase_id', $sale->id)
            ->get();
           return Response::json(['data' => $edit]);
        
    }

    public function LoadPreviousData(Request $request)
    {
        // return $request;
        // Find previous voucher number globally
        $prevoucher = SalePurchase::where('voucher_no', '<', $request->voucher_no)
        ->whereType('DIRECT SALESTAX INVOICE')->max('voucher_no');
        $sale = SalePurchase::where('voucher_no', $prevoucher)->whereType('DIRECT SALESTAX INVOICE')->first();
         $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with('salepurchase')
                    ->with('party:id,party_name,address')
                    ->whereType('DIRECT SALESTAX INVOICE')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();

          
        return Response::json(['data' => $edit]);
    }

    public function LoadNextData(Request $request)
    {
         // Find next voucher number globally
         $nextvoucher = SalePurchase::whereType('DIRECT SALESTAX INVOICE')
         ->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');
        $sale = SalePurchase::where('voucher_no', $nextvoucher)->whereType('DIRECT SALESTAX INVOICE')->first();
        $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
            ->with('salepurchase')
            ->with('party:id,party_name,address')
            ->whereType('DIRECT SALESTAX INVOICE')
            ->where('sale_purchase_id', $sale->id)
            ->get();
            return Response::json(['data' => $edit]); 
    }

    public function DeleteVoucher(Request $request)
    {
        // $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        //     ->where('voucher_name', 'DIRECT SALESTAX INVOICE')
        //     ->where('right_name', 'DELETE')
        //     ->first();
        // if (!$voucherRight) {
        //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        // }

        $sale = SalePurchase::whereType('DIRECT SALESTAX INVOICE')->where('voucher_no', $request->delete_voucher_no)->first();
        if ($sale) {
            // $record = DeliveryChallan::where('id', $sale->grn_dc_id)->first();
            // $record->update(['status' => 0]);
            // SalePurchase::findOrFail($sale->id)->whereType('SALESTAX INVOICE')->delete();
            SalePurchase::where('id', $sale->id)->whereType('DIRECT SALESTAX INVOICE')->delete();
            SalePurchaseDetail::whereType('DIRECT SALESTAX INVOICE')->where('sale_purchase_id', $sale->id)->delete();
            GeneralVoucher::where('v_type', 'DIRECT SALESTAX INVOICE')->where('voucher_id', $sale->id)->delete();
            Session::flash('flash_message', 'Direct SaleTax Voucher Deleted Successfully!');
            return redirect('direct-saletax-invoice');
            // return redirect()->back()->with('flash_message', 'Saletax Voucher Deleted Successfully!');
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

        return view('saletax-invoice.report', compact('customers'));
    }

    public function PrintVoucher(Request $request)
    {
        // return $request;
        File::cleanDirectory(base_path() . '/upload/salestax-invoice');
       $voucher_no = $request->voucher_no;
            // Find salevoucher by voucher_no globally (series is global)
            $salevoucher = SalePurchase::where('type', 'DIRECT SALESTAX INVOICE')->where('voucher_no', $voucher_no)->first();

                $salevoucherDetails = SalePurchaseDetail::with(['salepurchase' => function ($qry) {
                     $qry->with('party');
                     }])
                     ->with('product:id,product_name,uom,tax,code,packing')
                     ->where('sale_purchase_id', $salevoucher->id)
                     ->where('type', 'DIRECT SALESTAX INVOICE')
                     ->orderBy('id', 'asc')
                     ->get();
                     $warehouseTitle = Setting::first();
                     // Use the warehouse associated with the sale
                     $warehouse = Warehouse::where('id', $salevoucher->warehouse_id)->first();
                     $pdf = PDF::loadView('saletax-invoice.direct.invoice', compact('salevoucherDetails', 'warehouse', 'warehouseTitle'));
                $fileName =  'Direct-saletax-invoice' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/salestax-invoice/' . $fileName));
                return $fileName;
        
    }

    public function PrintDCVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/salestax-invoice');
        $voucher_no = $request->voucher_no;
        // Find salevoucher by voucher_no globally
        $salevoucher = SalePurchase::where('type', 'DIRECT SALESTAX INVOICE')->where('voucher_no', $voucher_no)->first();

        $salevoucherDetails = SalePurchaseDetail::with(['salepurchase' => function ($qry) {
            $qry->with('party');
        }])
            ->with('product:id,product_name,uom,tax,code,packing')
            ->where('sale_purchase_id', $salevoucher->id)
            ->where('type', 'DIRECT SALESTAX INVOICE')
            ->orderBy('id', 'asc')
            ->get();
        $warehouseTitle = Setting::first();
        $warehouse = Warehouse::where('id', $salevoucher->warehouse_id)->first();
        $pdf = PDF::loadView('saletax-invoice.direct.dc-invoice', compact('salevoucherDetails', 'warehouse', 'warehouseTitle'));
        $fileName = 'DC-Direct-saletax-invoice' . $voucher_no . '.pdf';
        $pdf->save(base_path('upload/salestax-invoice/' . $fileName));
        return $fileName;
    }

    // public function getdcRecord(Request $request)
    // {
    //     $challan_id = $request->dcn_id;
    //     $dcChallan = DeliveryChallan::where('id', $challan_id)->first();
    //     $voucher_no = $dcChallan->voucher_no;
    //     $partyID = $dcChallan->party_id;
    //     $DeliveryChallan = CustomerProduct::where('customer_id', $partyID)->first();
    //     if($DeliveryChallan){
    //         $status = '0';
    //             $data = DeliveryChallanDetails::with('customer_product')
    //         ->with(['delivery_challan' => function ($qry) {
    //             $qry->with('party:id,party_name,address', 'sale_order:id,voucher_no');
    //         }])
    //             ->with('product:id,product_name,uom,code')
    //             ->where('voucher_no', $voucher_no)
    //             ->where('type', 'DC')
    //             ->get();
    //     return Response::json(['data' => $data, 'status' => $status]);
    //     }else{
    //         $status = '1';
    //         $data = GodownStockDetail::with('product:id,product_name,uom,tax,code,packing', 'delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
    //         ->with('party:id,party_name,address')
    //         ->where('transaction_id', $dcChallan->id)
    //         ->where('type', 'DC')
    //         ->get();
    //     return Response::json(['data' => $data, 'status' => $status]);
    //     }

    //     // if ($dcChallan->type == 'CDC') {
    //     //     $data = DeliveryChallanDetails::with(['cusproduct' => function ($query) {
    //     //         $query->with('product');
    //     //     }])
    //     //         ->with('party:id,party_name,address')
    //     //         ->where('challan_id', $challan_id)
    //     //         ->where('customer_product_id', '!=', null)
    //     //         ->with('delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
    //     //         ->get();
    //     //     return Response::json(['data' => $data]);
    //     // } else {
    //     //     $data = GodownStockDetail::with('product:id,product_name,uom,tax,code,packing', 'delivery_challan:id,vehicle_no,driver_name,builty_no,driver_phoneno,transport_company,freight,party_id,voucher_date')
    //     //         ->with('party:id,party_name,address')
    //     //         ->where('transaction_id', $dcChallan->id)
    //     //         ->where('type', 'DC')
    //     //         ->get();
    //     //     return Response::json(['data' => $data]);
    //     // }
    // }
}
