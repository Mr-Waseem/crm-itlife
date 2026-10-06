<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SalePurchase;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use App\Models\SalePurchaseDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Session;
class DirectPurchaseTaxVoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
      
        $codes = 1;
        $sales = SalePurchase::whereType('DIRECT PURCHASETAX VOUCHER')->OrderBy('id', 'desc')->first();
        if ($sales) {
            $codes = $sales->voucher_no + 1;
        }

        // $DeliveryChallan = DeliveryChallan::where('type','DC')->whereStatus(0)->OrderBy('voucher_no', 'asc')->pluck('voucher_no', 'id')->prepend('Select Challan', '');
        $DeliveryChallan = DB::table('delivery_challans')
            ->join('parties', 'parties.id', '=', 'delivery_challans.party_id')
            ->select(DB::raw("delivery_challans.id,CONCAT(delivery_challans.voucher_no, '-', parties.party_name) AS  voucher_no")) 
            ->where('delivery_challans.type', 'DC')
            ->where('delivery_challans.status', 0)->orderBy('delivery_challans.id', 'asc')
            ->pluck('voucher_no','delivery_challans.id')
            ->prepend('Select Challans', '');
            $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`,"_", `product_price`,"_", `product_cost`,"_", `packing`,"_", `tax`) AS `id`,`product_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
            ->where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

        $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        return view('purchases.direct-purchasetax-voucher.index', compact('codes', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
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
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $customers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`'))
            ->where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

        $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        return view('purchases.direct-purchasetax-voucher.index', compact('data', 'customers', 'products', 'DeliveryChallan', 'warehouse',));
    }

    public function store(Request $request)
    {
       //return $request->all();
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
        $SaleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->first();
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT PURCHASETAX VOUCHER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

           
            $salePurchase = SalePurchase::where('id', $request->update_voucher_id)->where('type', 'DIRECT PURCHASETAX VOUCHER')->first();
            // return $request->all();
            $data = $request->all();
            $data['type'] = 'DIRECT PURCHASETAX VOUCHER';
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['updated_by'] = Auth::User()->id;
            $data['vehicle_no'] = $request->vehicle_no_;
            // $data['grn_dc_id'] = $request->dcn_id1;
            $salePurchase->update($data);
            // return "enter";
            SalePurchaseDetail::whereType('DIRECT PURCHASETAX VOUCHER')->where('sale_purchase_id', $salePurchase->id)->delete();
            GeneralVoucher::where('v_type', 'DIRECT PURCHASETAX VOUCHER')->where('voucher_id', $salePurchase->id)->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'DIRECT PURCHASETAX VOUCHER';
                $salePurchaseDetails->warehouse_id = Auth::User()->warehouse_id;
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
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i] == null ? 0 : $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type =  'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->narration = 'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->debit = $request->total[$i];
                $generalVoucher->save();
                
                 $generalVoucher = new GeneralVoucher();
                 $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                 $generalVoucher->other_head_id = $salePurchase->party_id;
                 $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                 $generalVoucher->product_id =  $request->product_id[$i];
                 $generalVoucher->date = $salePurchase->date;
                 $generalVoucher->voucher_no = $salePurchase->voucher_no;
                 $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                 $generalVoucher->strate = $request->st_rate[$i] == null ? 0 : $request->st_rate[$i];
                 $generalVoucher->stvalue = $request->sale_tax[$i] == null ? 0 : $request->sale_tax[$i];
                 $generalVoucher->quantity = $request->sale_qty[$i];
                 $generalVoucher->v_type = 'DIRECT PURCHASETAX VOUCHER';
                 $generalVoucher->narration = 'DIRECT PURCHASETAX VOUCHER';
                //  $generalVoucher->credit = $request->total[$i];
                 $generalVoucher->credit = $request->excl_val[$i];
                 $generalVoucher->save();

                 $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                $generalVoucher->account_head_id = $SaleWarehouse->tax_account_id;
                $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type = 'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->narration = 'DIRECT PURCHASETAX VOUCHER';
                // $generalVoucher->credit = $request->total[$i];
                // $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->credit = $request->sale_tax[$i];
                $generalVoucher->save();
            }
            // return "submit";
            Session::flash('flash_message', 'Direct PurchaseTax Voucher Updated Successfully!');
            return redirect('purchases/direct-purchasetax-voucher');
            // return redirect()->back()->with('flash_message', 'SaleTax Voucher Updated Successfully!');
        } else {
            // return $request->all();
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DIRECT PURCHASETAX VOUCHER')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
            $sales = SalePurchase::whereType('DIRECT PURCHASETAX VOUCHER')->OrderBy('id', 'desc')->first();
            if ($sales) {
                $codes = $sales->voucher_no + 1;
            }

            $data = $request->all();
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['type'] = 'DIRECT PURCHASETAX VOUCHER';
            // $data['grn_dc_id'] = $request->dcn_id;
            $data['vehicle_no'] = $request->vehicle_no_;
            $data['challan_type'] = 'DIRECT PURCHASETAX VOUCHER';
            $salePurchase = SalePurchase::create($data);


            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $salePurchaseDetails = new SalePurchaseDetail();
                
                $salePurchaseDetails->sale_purchase_id = $salePurchase->id;
                $salePurchaseDetails->voucher_no = $salePurchase->voucher_no;
                $salePurchaseDetails->date = $salePurchase->date;
                $salePurchaseDetails->type = 'DIRECT PURCHASETAX VOUCHER';
                $salePurchaseDetails->warehouse_id = Auth::User()->warehouse_id;
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
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type =  'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->narration = 'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->debit = $request->total[$i];
                // $generalVoucher->debit = $request->excl_val[$i];
                $generalVoucher->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
               $generalVoucher->account_head_id = $SaleWarehouse->sale_account_id;
                $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type = 'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->narration = 'DIRECT PURCHASETAX VOUCHER';
                // $generalVoucher->credit = $request->total[$i];
                $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->save();

                $generalVoucher = new GeneralVoucher();
                $generalVoucher->voucher_id = $salePurchase->id;
                 $generalVoucher->account_head_id = $SaleWarehouse->tax_account_id;
                $generalVoucher->other_head_id = $salePurchase->party_id;
                $generalVoucher->warehouse_id =  Auth::User()->warehouse_id;
                $generalVoucher->product_id =  $request->product_id[$i];
                $generalVoucher->date = $salePurchase->date;
                $generalVoucher->voucher_no = $salePurchase->voucher_no;
                $generalVoucher->rate = $request->rate[$i] == null ? 0 : $request->rate[$i];
                $generalVoucher->strate = $request->st_rate[$i];
                $generalVoucher->stvalue = $request->sale_tax[$i];
                $generalVoucher->quantity = $request->sale_qty[$i];
                $generalVoucher->v_type = 'DIRECT PURCHASETAX VOUCHER';
                $generalVoucher->narration = 'DIRECT PURCHASETAX VOUCHER';
                // $generalVoucher->credit = $request->total[$i];
                // $generalVoucher->credit = $request->excl_val[$i];
                $generalVoucher->credit = $request->sale_tax[$i];
                $generalVoucher->save();
            }
            // return "sub";
            // $record = DeliveryChallan::where('id', $request->dcn_id)->first();
            // $record->update(['status' => 1]);
            Session::flash('flash_message', 'Direct PurchaseTax Voucher Added Successfully!');
            return redirect('purchases/direct-purchasetax-voucher');
            return redirect()->back()->with('flash_message', 'Direct PurchaseTax Voucher Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $sale = SalePurchase::whereType('DIRECT PURCHASETAX VOUCHER')->where('voucher_no', $request->voucher_no)->first();
      
                   $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                   ->with('salepurchase')
                       ->with('party:id,party_name,address')
                       ->whereType('DIRECT PURCHASETAX VOUCHER')
                       ->where('sale_purchase_id', $sale->id)
                       ->get();
           return Response::json(['data' => $edit]);
        
    }

    public function LoadPreviousData(Request $request)
    {
        $prevoucher = SalePurchase::where('voucher_no', '<', $request->voucher_no)->whereType('DIRECT PURCHASETAX VOUCHER')->max('voucher_no');
        $sale = SalePurchase::where('voucher_no', $prevoucher)->whereType('DIRECT PURCHASETAX VOUCHER')->first();
         $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                    ->with('salepurchase')
                    ->with('party:id,party_name,address')
                    ->whereType('DIRECT PURCHASETAX VOUCHER')
                    ->where('sale_purchase_id', $sale->id)
                    ->get();

          
        return Response::json(['data' => $edit]);
    }

    public function LoadNextData(Request $request)
    {
         $nextvoucher = SalePurchase::whereType('DIRECT PURCHASETAX VOUCHER')->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');
        $sale = SalePurchase::where('voucher_no', $nextvoucher)->whereType('DIRECT PURCHASETAX VOUCHER')->first();
       
                   $edit = SalePurchaseDetail::with('product:id,product_name,uom,tax,code,packing')
                       ->with('salepurchase')
                       ->with('party:id,party_name,address')
                       ->whereType('DIRECT PURCHASETAX VOUCHER')
                       ->where('sale_purchase_id', $sale->id)
                       ->get();
   
            return Response::json(['data' => $edit]);
           
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DIRECT PURCHASETAX VOUCHER')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $sale = SalePurchase::whereType('DIRECT PURCHASETAX VOUCHER')->where('voucher_no', $request->delete_voucher_no)->first();
        if ($sale) {
            // $record = DeliveryChallan::where('id', $sale->grn_dc_id)->first();
            // $record->update(['status' => 0]);
            // SalePurchase::findOrFail($sale->id)->whereType('SALESTAX INVOICE')->delete();
            SalePurchase::where('id', $sale->id)->whereType('DIRECT PURCHASETAX VOUCHER')->delete();
            SalePurchaseDetail::whereType('DIRECT PURCHASETAX VOUCHER')->where('sale_purchase_id', $sale->id)->delete();
            GeneralVoucher::where('v_type', 'DIRECT PURCHASETAX VOUCHER')->where('voucher_id', $sale->id)->delete();
            Session::flash('flash_message', 'Direct PurchaseTax Voucher Deleted Successfully!');
            return redirect('purchases.direct-purchasetax-voucher');
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

        return view('purchases.direct-purchasetax-voucher.report', compact('customers'));
    }

    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/direct-purchasetax-voucher');
       $voucher_no = $request->voucher_no;
         $salevoucher = SalePurchase::where('type', 'DIRECT PURCHASETAX VOUCHER')->where('voucher_no', $voucher_no)->first();
       
             $salevoucherDetails = SalePurchaseDetail::with(['salepurchase' => function ($qry) {
                $qry->with('party');
                }])
                ->with('product:id,product_name,uom,tax,code,packing')
                ->where('sale_purchase_id', $salevoucher->id)
                ->where('type', 'DIRECT PURCHASETAX VOUCHER')
                ->orderBy('id', 'asc')
                ->get();

                $pdf = PDF::loadView('purchases.direct-purchasetax-voucher.invoice', compact('salevoucherDetails'));
                $fileName =  'Purchasetax-voucher' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/direct-purchasetax-voucher/' . $fileName));
                return $fileName;
        
    }
   
}
