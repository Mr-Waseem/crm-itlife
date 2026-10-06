<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\CustomerProduct;
use App\Models\DeliveryChallan;
use App\Models\GodownStockDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DeliveryChallanDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class CustomerDeliveryChallanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
        $deliveryChallan = DeliveryChallan::OrderBy('id', 'desc')->first();
        if ($deliveryChallan) {
            $codes = $deliveryChallan->voucher_no + 1;
        }
        $products = array('' => 'Select Product');
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            ->select('*', DB::raw("CONCAT(parties.id,'_',parties.party_name,'_',parties.address) as id,party_name"))
            ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->where('parties.account_group_id', '1')
            ->Orwhere('parties.account_group_id', '7')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('Select Party Name', '');

        $salerOrderNo = SaleOrder::orderBy('id', 'asc')->pluck('voucher_no', 'id')->prepend('Sale Order No', '');

        return view('customer-delivery-challan.index', compact('codes', 'customers', 'products', 'salerOrderNo'));
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required|date',
            'voucher_no' => 'required',
            'party_name' => 'required',
            'order_date' => 'required|date',
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

        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CUSTOMER DELIVERY CHALLAN')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $deliveryChallan = DeliveryChallan::find($request->update_voucher_id);
            $deliveryChallan->update($request->all());

            DeliveryChallanDetails::where('challan_id', $deliveryChallan->id)->delete();

            $totalQty = 0;
            $totalsale_qty = 0;

            $count = count($request->product_id);

            for ($i = 0; $i < $count; $i++) {
                $deliveryChallanDetails = new DeliveryChallanDetails();
                $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->customer_product_id = $request->product_id[$i];
                $deliveryChallanDetails->voucher_no = $deliveryChallan->voucher_no;
                $deliveryChallanDetails->voucher_date = $request->voucher_date;
                $deliveryChallanDetails->type ='CDC';
                $deliveryChallanDetails->warehouse_id = Auth::User()->warehouse_id;
                $deliveryChallanDetails->quantity = $request->qty[$i];
                $deliveryChallanDetails->packing = $request->packing[$i];
                $deliveryChallanDetails->sale_qty = $request->sale_qty[$i];
                $deliveryChallanDetails->comments = $request->comment[$i];
                $deliveryChallanDetails->created_by = Auth::User()->id;
                $deliveryChallanDetails->save();

                $productt=CustomerProduct::where('id',$request->product_id[$i])->first();

                $godownstockDetails=new GodownStockDetail();
                $godownstockDetails->voucher_no = $deliveryChallan->voucher_no;
                $godownstockDetails->transaction_id = $deliveryChallan->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date= $deliveryChallan->voucher_date;
                $godownstockDetails->type = 'CDC';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->party_id =$request->party_id;
                $godownstockDetails->product_id = $productt->product_id;
                $godownstockDetails->qty_in =0;
                $godownstockDetails->qty_out =$request->qty[$i];
                $godownstockDetails->demand_qty =$request->sale_qty[$i];
                $godownstockDetails->remarks = $request->comment[$i];
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();

                $totalQty +=  $request->qty[$i];
                $totalsale_qty += $request->sale_qty[$i];
            }
            $deliveryChallan->total_qty = $totalQty;
            $deliveryChallan->total_sale_qty = $totalsale_qty;
            $deliveryChallan->created_by = Auth::User()->id;
            $deliveryChallan->save();
            return redirect()->back()->with('flash_message', 'Customer Delivery Challan Updated Successfully!');
        } else {
           // return $request;
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CUSTOMER DELIVERY CHALLAN')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $codes = 1;
            $deliveryChallan = DeliveryChallan::OrderBy('id', 'desc')->first();
            if ($deliveryChallan) {
                $codes = $deliveryChallan->voucher_no + 1;
            }

            $data = $request->all();
            $data['voucher_no'] = $codes;
            $data['type'] = 'CDC';
            $deliveryChallan = DeliveryChallan::create($data);

            $totalQty = 0;
            $totalsale_qty = 0;
               
            $count = count($request->product_id);
            $productt=CustomerProduct::where('id',$request->product_id)->first();
            for ($i = 0; $i < $count; $i++) {
                $deliveryChallanDetails = new DeliveryChallanDetails();
                $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->customer_product_id = $request->product_id[$i];
                $deliveryChallanDetails->voucher_no = $deliveryChallan->voucher_no;
                $deliveryChallanDetails->voucher_date = $request->voucher_date;
                $deliveryChallanDetails->type ='CDC';
                $deliveryChallanDetails->warehouse_id = Auth::User()->warehouse_id;
                $deliveryChallanDetails->quantity = $request->qty[$i];
                $deliveryChallanDetails->packing = $request->packing[$i];
                $deliveryChallanDetails->sale_qty = $request->sale_qty[$i];
                $deliveryChallanDetails->comments = $request->comment[$i];
                $deliveryChallanDetails->created_by = Auth::User()->id;
                $deliveryChallanDetails->save();

                $productt=CustomerProduct::where('id',$request->product_id[$i])->first();

                $godownstockDetails=new GodownStockDetail();
                $godownstockDetails->voucher_no = $deliveryChallan->voucher_no;
                $godownstockDetails->transaction_id = $deliveryChallan->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date= $deliveryChallan->voucher_date;
                $godownstockDetails->type = 'CDC';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->party_id =$request->party_id;
                $godownstockDetails->product_id = $productt->product_id;
                $godownstockDetails->qty_in =0;
                $godownstockDetails->qty_out =$request->qty[$i];
                $godownstockDetails->demand_qty =$request->sale_qty[$i];
                $godownstockDetails->remarks = $request->comment[$i];
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->save();

                $totalQty +=  $request->qty[$i];
                $totalsale_qty += $request->sale_qty[$i];
            }
            $deliveryChallan->total_qty = $totalQty;
            $deliveryChallan->total_sale_qty = $totalsale_qty;
            $deliveryChallan->created_by = Auth::User()->id;
            $deliveryChallan->save();


            return redirect()->back()->with('flash_message', 'Customer Delivery Challan Added Successfully!');
        }
        return redirect()->back()->with('error_message', 'Something went wrong!!!');
    }

    public function editData(Request $request)
    {
        $edit = DeliveryChallan::where('voucher_no', $request->voucher_no)->where('type','CDC')->first();
        if ($edit) {
            $data = DeliveryChallanDetails::with(['cusproduct'=>function($qry){
                $qry->with('product:id,uom,packing');
            }])
                 
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party');
                }])
                ->where('type','CDC')   
                ->where('challan_id', $edit->id)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $deliveryChallan = DeliveryChallan::where('voucher_no', '<', $request->voucher_no)->where('type','CDC')->max('voucher_no');

        if ($deliveryChallan) {
            $data = DeliveryChallanDetails::with(['cusproduct'=>function($qry){
                $qry->with('product:id,uom,packing');
            }])
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party');
                }])
                ->where('type','CDC')   
                ->where('voucher_no', $deliveryChallan)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $deliveryChallan = DeliveryChallan::where('voucher_no', '>', $request->voucher_no)->where('type','CDC')->min('voucher_no');

        if ($deliveryChallan) {
            $data = DeliveryChallanDetails::with(['cusproduct'=>function($qry){
                $qry->with('product:id,uom,packing');
            }])
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party');
                }])
                ->where('type','CDC')   
                ->where('voucher_no', $deliveryChallan)
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'CUSTOMER DELIVERY CHALLAN')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        $deliveryChallan = DeliveryChallan::where('voucher_no', $request->delete_voucher_no)->where('type','CDC')->first();
        if ($deliveryChallan) {
            DeliveryChallan::findOrFail($deliveryChallan->id)->where('type','CDC')->delete();
            DeliveryChallanDetails::where('challan_id', $deliveryChallan->id)->where('type','CDC')->delete();
               GodownStockDetail::where('transaction_id', $deliveryChallan->id)->where('type','CDC')->delete();

            return redirect()->back()->with('flash_message', 'Customer Delivery Challan Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'CUSTOMER DELIVERY CHALLAN')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        File::cleanDirectory(base_path() . '/upload/Customer-delivery-challan');
        $voucher_no = $request->voucher_no;
        $DeliveryChallan = DeliveryChallan::where('voucher_no', $voucher_no)->where('type','CDC')->first();
        if ($DeliveryChallan) {
            $deliverychallanDetail = DeliveryChallanDetails::with(['delivery_challan' => function ($qry) {
                $qry->with('party:id,party_name,address', 'sale_order:id,voucher_no');
            }])
            ->with(['cusproduct'=>function($qry){
                    $qry->with('product:id,uom,packing');
                }])
                ->where('voucher_no', $DeliveryChallan->voucher_no)
                ->where('type','CDC')
                ->get();

            $pdf = PDF::loadView('customer-delivery-challan.invoice', compact('deliverychallanDetail'));
            $fileName =  'Customer-delivery-challan' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/customer-delivery-challan/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }
    public function getCustomerProduct(Request $request)
    {
        $customerPro = CustomerProduct::with('product:id,product_name,uom,packing')
            ->where('customer_id', $request->customer_id)->get();
        return json_encode($customerPro);
    }
}
