<?php

namespace App\Http\Controllers;

use App\Models\UOM;
use App\Models\Party;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\CustomerProduct;
use App\Models\SaleOrderDetails;
use Illuminate\Support\Facades\DB;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SaleDemandController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
        $salesOrder = SaleOrder::where('type','SALE DEMAND')->max('voucher_no');
        if ($salesOrder) {
            $codes = $salesOrder + 1;
        }
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `uom`, "_", `packing`, "_", `tax`, "_", `product_name`, "_", `code`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            // ->where('product_type', 'Finish')
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('code', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $customers = Party::where('role', '=', 'Customer')
            ->where('type', 'Un Registered')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');
        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('GRAMS', '9_GRAMS');
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id');
        $payment_mode = array('' => 'Select Type', 'Cash' => 'Cash', 'Credit' => 'Credit');
        $shipment_term = array('' => 'Select Type', 'Supplier' => 'Supplier', 'Buyer' => 'Buyer');
        return view('sales-demand.index', compact('codes', 'customers', 'uoms', 'warehouse', 'payment_mode', 'shipment_term', 'products'));
    }
    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'voucher_no' => 'required',
            'party_name' => 'required',
            'payment_mode' => 'required',
            'shipment_term' => 'required',
            'po_date' => 'required',
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
        // if (!isset($request->price)) {
        //     return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        // }
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALE DEMAND')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return $request;
            $saleOrder = SaleOrder::find($request->update_voucher_id);
            $data = $request->all();
            $data['updated_by'] = Auth::User()->id;
            $data['type'] = 'SALE DEMAND';
            $data['status'] = 0;
            $saleOrder->update($data);
            SaleOrderDetails::where('sale_order_id', $saleOrder->id)->delete();
// return $request;
            $totalQty = 0;
            $totalpacking = 0;
            $totalOrderQty = 0;
            $totalSaleAmount = 0;
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $saleOrderDetails = new SaleOrderDetails();
                $saleOrderDetails->sale_order_id = $saleOrder->id;
                $saleOrderDetails->voucher_no = $saleOrder->voucher_no;
                $saleOrderDetails->party_id = $saleOrder->party_id;
                $saleOrderDetails->type ='SALE DEMAND';
                $saleOrderDetails->voucher_date = $saleOrder->voucher_date;
                $saleOrderDetails->product_id = $request->product_id[$i];
                $saleOrderDetails->qty = $request->qty[$i];
                $saleOrderDetails->packing = $request->packing[$i];
                $saleOrderDetails->order_qty = $request->order_qty[$i];
                $saleOrderDetails->remaing_qty = $request->order_qty[$i];
                $saleOrderDetails->sale_rate = $request->price[$i];
                $saleOrderDetails->delivery_date = $request->delivery_date[$i];
                $saleOrderDetails->remark = $request->remark[$i]; 
                $saleOrderDetails->status = 0; 
                $saleOrderDetails->save();
                $totalQty +=  $request->qty[$i];
                $totalpacking +=  $request->packing[$i];
                $totalOrderQty +=  $request->order_qty[$i];
            }
            $saleOrder->total_qty = $totalQty;
            $saleOrder->total_packing = $totalpacking;
            $saleOrder->total_order_qty = $totalOrderQty;
            $saleOrder->save();
            return redirect()->back()->with('flash_message', 'Sale Demand Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALE DEMAND')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
            $salesOrder = SaleOrder::where('type','SALE DEMAND')->max('voucher_no');
            if ($salesOrder) {
                $codes = $salesOrder + 1;
            }
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['type'] = 'SALE DEMAND';
            $data['status'] = 0;
            $saleOrder = SaleOrder::create($data);
            $totalQty = 0;
            $totalpacking = 0;
            $totalOrderQty = 0;
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $saleOrderDetails = new SaleOrderDetails();
                $saleOrderDetails->sale_order_id = $saleOrder->id;
                $saleOrderDetails->voucher_no = $saleOrder->voucher_no;
                $saleOrderDetails->party_id = $saleOrder->party_id;
                $saleOrderDetails->type ='SALE DEMAND';
                $saleOrderDetails->voucher_date = $saleOrder->voucher_date;
                $saleOrderDetails->product_id = $request->product_id[$i];
                $saleOrderDetails->qty = $request->qty[$i];
                $saleOrderDetails->packing = $request->packing[$i];
                $saleOrderDetails->order_qty = $request->order_qty[$i];
                $saleOrderDetails->remaing_qty = $request->order_qty[$i];
                $saleOrderDetails->sale_rate = $request->price[$i];
                $saleOrderDetails->delivery_date = $request->delivery_date[$i];
                $saleOrderDetails->remark = $request->remark[$i];
                $saleOrderDetails->status = 0;
                $saleOrderDetails->save();
                $totalQty +=  $request->qty[$i];
                $totalpacking +=  $request->packing[$i];
                $totalOrderQty +=  $request->order_qty[$i];
            }
            $saleOrder->total_qty = $totalQty;
            $saleOrder->total_packing = $totalpacking;
            $saleOrder->total_order_qty = $totalOrderQty;
            $saleOrder->save();
            return redirect()->back()->with('flash_message', 'Sale Demand Added Successfully!');
        }
    }
    public function editData(Request $request)
    {
        // $edit = SaleOrder::where('voucher_no', $request->voucher_no)->where('created_by', Auth::User()->id)->first();
         $order = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', $request->voucher_no)->first();
        $status = '';
        if ($order) {
            $status = 1;
            $edit = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', $request->voucher_no)->first();
            $editt = SaleOrderDetails::where('sale_order_id', $order->id)
                ->with('product:id,uom,product_name,code')
                ->with(['sale_order' => function ($query) {
                    $query->with('party');
                }])
                ->get();
            return Response::json(['data' => $editt, 'status' => $status]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function getProduct(Request $request){
        // return $request->VoucherDate;
        $ProductID = $request->productID;
        $VoucherDate= $request->VoucherDate;
        $data = Product::with(['productRates' => function($query) use ($ProductID, $VoucherDate){
            // $query->orderby('id', 'desc')->first();
            $query->where('product_id', $ProductID);
             $query->whereDate('voucher_date', '<', $VoucherDate);
             $query->orderby('voucher_date', 'desc')->first();
        }])
        ->where('id', $request->productID)->first();
        return $data;
    }

    public function LoadPreviousData(Request $request)
    {
        //$saleOrder = SaleOrder::where('voucher_no', '<', $request->voucher_no)->where('created_by', Auth::User()->id)->max('voucher_no');
        $saleOrder = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', '<', $request->voucher_no)->max('voucher_no');
        $status = '';
        if ($saleOrder) {
                $status = 1;
                $saleOrder = SaleOrder::where('voucher_no', '<', $request->voucher_no)->where('type','SALE DEMAND')->max('voucher_no');
                $data = SaleOrderDetails::with('product:id,uom,product_name,code')
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->where('voucher_no', $saleOrder)
                    ->where('type','SALE DEMAND')
                    ->get();
                return Response::json(['data' => $data, 'status' => $status]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        // $saleOrder = SaleOrder::where('voucher_no', '>', $request->voucher_no)->where('created_by', Auth::User()->id)->min('voucher_no');
        $saleOrder = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');

        $status = '';
        if ($saleOrder) {
                $status = 1;
                $saleOrder = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', '>', $request->voucher_no)->min('voucher_no');
                $data = SaleOrderDetails::with('product:id,uom,product_name,code')
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->where('voucher_no', $saleOrder)
                    ->where('type','SALE DEMAND')
                    ->get();
                return Response::json(['data' => $data, 'status' => $status]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALE DEMAND')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // $saleOrder = SaleOrder::where('voucher_no', $request->delete_voucher_no)->where('created_by', Auth::User()->id)->first();
        $saleOrder = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', $request->delete_voucher_no)->first();
        if ($saleOrder) {
            SaleOrder::findOrFail($saleOrder->id)->delete();
            SaleOrderDetails::where('sale_order_id', $saleOrder->id)->where('type','SALE DEMAND')->delete();
            return redirect()->back()->with(Toastr::info('Sale Demand Deleted Successfully!'));
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }

    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALE DEMAND')
            ->where('right_name', 'PRINT')
            ->first();
        File::cleanDirectory(base_path() . '/upload/sales-demand');
        $voucher_no = $request->voucher_no;
        $status = '';
        // $saleorder = SaleOrder::where('voucher_no', $voucher_no)->where('created_by', Auth::User()->id)->first();
        $saleorder = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', $voucher_no)->first();
        if ($saleorder) {
            $saleorderDetails = SaleOrderDetails::with(['sale_order' => function ($qry) {
                $qry->with('party:id,party_name,address', 'user:id,name');
            }])
                ->with(['customer_product' => function ($query) {
                    $query->with('product:id,uom,product_code');
                }])
                ->where('voucher_no', $voucher_no)
                ->orderBy('id', 'asc')
                ->first();
            if ($saleorderDetails->customer_product) {
                $status == 0;
                $voucher_no = $request->voucher_no;
                $saleorderDetails = SaleOrderDetails::with(['sale_order' => function ($qry) {
                    $qry->with('party:id,party_name,address', 'user:id,name');
                }])
                    ->with(['customer_product' => function ($query) {
                        $query->with('product:id,uom,code');
                    }])
                    ->where('voucher_no', $voucher_no)
                    ->where('type','SALE DEMAND')
                    ->orderBy('id', 'asc')
                    ->get();
                $pdf = PDF::loadView('sales-demand.invoice', compact('saleorderDetails', 'status'));
                $fileName =  'sales-demand' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/sales-demand/' . $fileName));
                return $fileName;
            } else {
                $status == 1;
                $voucher_no = $request->voucher_no;
                $saleorderr = SaleOrder::where('type','SALE DEMAND')->where('voucher_no', $voucher_no)->first();
                if($saleorderr){
                $saleorderDetails = SaleOrderDetails::with(['sale_order' => function ($qry) {
                    $qry->with('party:id,party_name,address', 'user:id,name');
                }])
                    ->with('product:id,uom,code,product_name')
                    ->where('voucher_no', $voucher_no)
                    ->where('type','SALE DEMAND')
                    ->orderBy('id', 'asc')
                    ->get();
                $pdf = PDF::loadView('sales-demand.invoice', compact('saleorderDetails', 'status'));
                $fileName =  'sales-demand' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/sales-demand/' . $fileName));
                return $fileName;
            }
        }
        } else {
            return false;
        }
    }
    public function getCustomerProduct(Request $request)
    {
        // return "enter";
        $products = array();
        $company = false;
        $customerproduct = CustomerProduct::with('product:id,packing,tax,uom')
            ->where('customer_id', $request->cat_id)->count();
        if ($customerproduct > 0) {
            // return "cus";
            $products = CustomerProduct::with('product:id,packing,tax,uom')->where('customer_id', $request->cat_id)->get();
        } else {
            // return "com";
            $company = true;
            $products = Product::where('product_type', 'Finish')->OrderBy('id', 'asc')->get();
        }
        return json_encode(['products' => $products, 'company' => $company]);
    }
}
