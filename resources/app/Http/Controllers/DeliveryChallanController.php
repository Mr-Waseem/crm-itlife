<?php

namespace App\Http\Controllers;

use App\Models\CustomerProduct;
use App\Models\Party;
use App\Models\Product;
use App\Models\SaleOrder;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\DeliveryChallan;
use App\Models\SaleOrderDetails;
use App\Models\GodownStockDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DeliveryChallanDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class DeliveryChallanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return "dd";
        $codes = 1;
        $deliveryChallan = DeliveryChallan::where('type', 'DC')->OrderBy('id', 'desc')->first();
        if ($deliveryChallan) {
            $codes = $deliveryChallan->voucher_no + 1;
        }
        // $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `packing`,"_", `product_price`) AS `id`,  `product_name`'))
        //     ->where('warehouse_id', 11)
        //     ->OrderBy('id', 'asc')
        //     ->pluck('product_name', 'id')
        //     ->prepend('Select Product', '');

        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `packing`,"_", `product_price`,"_", `code`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('warehouse_id', 11)
        ->OrderBy('code', 'asc')
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

           $salerOrderNo = DB::table('sale_orders')
            ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
            ->select(DB::raw("sale_orders.id,CONCAT(sale_orders.voucher_no, '-', parties.party_name, '-', sale_orders.po_no) AS  voucher_no")) // Concatenating the columns
            ->where('sale_orders.type', 'SALE ORDER')
            ->where('sale_orders.status', 0)->orderBy('sale_orders.id', 'asc')
            ->pluck('voucher_no','sale_orders.id')
            ->prepend('Sale Order No', '');

        return view('delivery-challan.index', compact('codes', 'customers', 'products', 'salerOrderNo'));
    }

    public function store(Request $request)
    {
        //  return $request->all();
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required|date',
            'voucher_no' => 'required',
            // 'party_name' => 'required',
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
                ->where('voucher_name', 'DC ORDER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            
            // return $request->sale_order_no_edit;
           $saleOrder = SaleOrder::where('voucher_no', $request->sale_order_no_edit)
            ->where('type','SALE ORDER')->first();

             
           $deliveryChallan = DeliveryChallan::find($request->update_voucher_id);
            $deliveryChallan->update($request->all());
            // return $deliveryChallan;
            $deliveryChallan->warehouse_id = Auth::user()->warehouse_id;
            $deliveryChallan->sale_order_no = $saleOrder['id'];
           $deliveryChallan->updated_by = Auth::user()->id;
            $deliveryChallan->save();
            DeliveryChallanDetails::where('challan_id', $deliveryChallan->id)->delete();
            GodownStockDetail::where('transaction_id', $deliveryChallan->id)->delete();
            
            $totalQty = 0;
            $totalsale_qty = 0;

            $count = count($request->product_id);

            for ($i = 0; $i < $count; $i++) {
                $deliveryChallanDetails = new DeliveryChallanDetails();
                $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->product_id = $request->product_id[$i];
                $deliveryChallanDetails->voucher_no = $deliveryChallan->voucher_no;
                $deliveryChallanDetails->sale_order_no = $deliveryChallan->sale_order_no;
                $deliveryChallanDetails->party_id = $deliveryChallan->party_id;
                $deliveryChallanDetails->order_detail_id = $request->order_detail_id[$i];
                $deliveryChallanDetails->po_no = $request->po_no;
                $deliveryChallanDetails->type = 'DC';
                $deliveryChallanDetails->voucher_date = $request->voucher_date;
                $deliveryChallanDetails->warehouse_id = Auth::User()->warehouse_id;
                $deliveryChallanDetails->demandqty = $request->demandqty[$i];
                $deliveryChallanDetails->demandPCS = $request->demandPCS[$i];
                $deliveryChallanDetails->quantity = $request->qty[$i];
                $deliveryChallanDetails->packing = $request->packing[$i];
                $deliveryChallanDetails->sale_qty = $request->sale_qty[$i];
                $deliveryChallanDetails->comments = $request->comment[$i];
                $deliveryChallanDetails->sale_rate = $request->sale_rate[$i];
                $deliveryChallanDetails->tax_rate = $request->tax_rate[$i];
                $deliveryChallanDetails->created_by = Auth::User()->id;
                $deliveryChallanDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $deliveryChallan->voucher_no;
                $godownstockDetails->transaction_id = $deliveryChallan->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date = $deliveryChallan->voucher_date;
                $godownstockDetails->type = 'DC';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->party_id = $request->party_id;

                // $customerProduct = CustomerProduct::where('product_id', $request->product_id[$i])->first();
                // if($customerProduct){
                //     $godownstockDetails->product_id = $customerProduct->product_id;
                // }else{
                    $godownstockDetails->product_id = $request->product_id[$i];
                // }
                $godownstockDetails->qty_in = 0;
                // $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->qty_out = $request->sale_qty[$i];
                $godownstockDetails->demand_qty = $request->demandPCS[$i];
                $godownstockDetails->rate = $request->tax_rate[$i];
                $godownstockDetails->remarks = $request->comment[$i];
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = $request->sale_rate[$i];
                $godownstockDetails->save();
                
                // $totalQty +=  $request->qty[$i];
                // $totalsale_qty += $request->sale_qty[$i];
            }
            // $deliveryChallan->total_qty = $totalQty;
            // $deliveryChallan->total_sale_qty = $totalsale_qty;
            // $deliveryChallan->created_by = Auth::User()->id;
            // $deliveryChallan->save();


            return redirect()->back()->with('flash_message', 'Delivery Challan Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DC ORDER')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            //  return $request;
            
            $codes = 1;
            $deliveryChallan = DeliveryChallan::where('type', 'DC')->OrderBy('id', 'desc')->first();
            if ($deliveryChallan) {
                $codes = $deliveryChallan->voucher_no + 1;
            }
            $data = $request->all();
            $data['voucher_no'] = $codes;
            $data['type'] = 'DC';
            $data['status'] = 0;
            $data['warehouse_id'] = Auth::user()->warehouse_id;
            $data['created_by'] = Auth::user()->id;
            
           $deliveryChallan = DeliveryChallan::create($data);
        //    return "ddd";
        //    $deliveryChallan->created_by = Auth::user()->id;
        //    $deliveryChallan->save();
           
            $totalQty = 0;
            $totalsale_qty = 0;
           
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
             
                $deliveryChallanDetails = new DeliveryChallanDetails();
                $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->product_id = $request->product_id[$i];
                $deliveryChallanDetails->voucher_no = $deliveryChallan->voucher_no;
                $deliveryChallanDetails->sale_order_no = $deliveryChallan->sale_order_no;
                $deliveryChallanDetails->party_id = $deliveryChallan->party_id;
                $deliveryChallanDetails->order_detail_id = $request->order_detail_id[$i];
                $deliveryChallanDetails->po_no = $request->po_no;
                $deliveryChallanDetails->type = 'DC';
                $deliveryChallanDetails->voucher_date = $request->voucher_date;
                $deliveryChallanDetails->warehouse_id = Auth::User()->warehouse_id;
                $deliveryChallanDetails->demandqty = $request->demandqty[$i];
                $deliveryChallanDetails->demandPCS = $request->demandPCS[$i];
                $deliveryChallanDetails->quantity = $request->qty[$i];
                $deliveryChallanDetails->packing = $request->packing[$i];
                $deliveryChallanDetails->sale_qty = $request->sale_qty[$i];
                $deliveryChallanDetails->comments = $request->comment[$i];
                $deliveryChallanDetails->sale_rate = $request->sale_rate[$i];
                $deliveryChallanDetails->tax_rate = $request->tax_rate[$i];
                $deliveryChallanDetails->created_by = Auth::User()->id;
                $deliveryChallanDetails->save();

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $deliveryChallan->voucher_no;
                $godownstockDetails->transaction_id = $deliveryChallan->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date = $deliveryChallan->voucher_date;
                $godownstockDetails->type = 'DC';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->party_id = $request->party_id;

                // $customerProduct = CustomerProduct::where('product_id', $request->product_id[$i])->first();
                // if($customerProduct){
                //     $godownstockDetails->product_id = $customerProduct->product_id;
                // }else{
                    $godownstockDetails->product_id = $request->product_id[$i];
                // }
                $godownstockDetails->qty_in = 0;
                // $godownstockDetails->qty_out = $request->qty[$i];
                $godownstockDetails->qty_out = $request->sale_qty[$i];
                $godownstockDetails->demand_qty = $request->demandPCS[$i];
                $godownstockDetails->rate = $request->tax_rate[$i];
                $godownstockDetails->remarks = $request->comment[$i];
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = $request->sale_rate[$i];
                $godownstockDetails->save();

                // $totalQty +=  $request->qty[$i];
                // $totalsale_qty += $request->sale_qty[$i];
            }
            // return "d1";
            // $deliveryChallan->total_qty = $totalQty;
            // $deliveryChallan->total_sale_qty = $totalsale_qty;
            // $deliveryChallan->created_by = Auth::User()->id;
            // $deliveryChallan->save();

            // $update = Saleorder::find($deliveryChallan->sale_order_no);
            // $update->update(['status' => 1]);

            return redirect()->back()->with('flash_message', 'Delivery Challan Added Successfully!');
        }
        return redirect()->back()->with('error_message', 'Something went wrong!!!');
    }

    public function editData(Request $request)
    {
        // $edit = DeliveryChallan::where('voucher_no', $request->voucher_no)->where('type', 'DC')->first();

    //     $edit = DeliveryChallan::where('voucher_no', $request->voucher_no)->where('type', 'DC')->first();
    //     //voucher no 5
    //    $VoucherNo = $edit->voucher_no;
    //    $SaleOrder = DeliveryChallan::where('voucher_no', '=', $edit->voucher_no)->where('type', 'DC')->get();
    //    $SaleOrderNo = $SaleOrder[0]->sale_order_no;
    //    if ($edit) {
    //         $data = DeliveryChallanDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
    //             $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
    //                         $query->where('sale_order_no', '=', $SaleOrderNo);
    //                         $query->where('voucher_no', '<=', $VoucherNo);
    //                      }]);
    //          }])
    //             ->with(['delivery_challan' => function ($query) {
    //                 $query->with('party', 'sale_order:id,voucher_no');
    //             }])
    //             ->where('voucher_no', $VoucherNo)
    //             ->where('type', 'DC')
    //             ->get();

    //         return Response::json(['data' => $data]);
    //     } else {
    //         return Response::json(['data' => '']);
    //     }
      $edit = DeliveryChallan::where('voucher_no', $request->voucher_no)->where('type', 'DC')->first();
      $VoucherNo = $edit->voucher_no;
    //   $VoucherNo = $deliveryChallan;
    //    $deliveryChallan = DeliveryChallan::where('voucher_no', '=', $VoucherNo)->where('type', 'DC')->min('voucher_no');
    //    return $VoucherNo = $deliveryChallan;

    $status = '';
     $SaleOrder = DeliveryChallan::where('voucher_no', '=', $VoucherNo)->where('type', 'DC')->first();
     $SaleOrderNo = $SaleOrder->sale_order_no;
    $re = DeliveryChallanDetails::where('voucher_no', $VoucherNo)->where('type', 'DC')->first();
     $products = CustomerProduct::with('product')
     ->where('product_id', $re->product_id)
     ->where('customer_id', $SaleOrder->party_id)
     ->count();
    if ($products > 0) {
         $status = 0;
       $data = DeliveryChallanDetails::
       with(['customer_product' => function($query) use ($SaleOrderNo, $VoucherNo){
        $query->with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
            $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                        $query->where('sale_order_no', '=', $SaleOrderNo);
                            $query->where('voucher_no', '<=', $VoucherNo);
                        }]);
        }]);
            }])
            // ->with('dc_details2') 
            ->with(['delivery_challan' => function ($query) {
                $query->with('party', 'sale_order:id,voucher_no');
            }])
            ->where('voucher_no', $VoucherNo)
            ->where('type', 'DC')
            ->get();

        return Response::json(['data' => $data, 'status' => $status]);
    } else {
        $status = 1;
            $data = DeliveryChallanDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
            $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                        $query->where('sale_order_no', '=', $SaleOrderNo);
                            $query->where('voucher_no', '<=', $VoucherNo);
                        }]);
            }])
            ->with(['delivery_challan' => function ($query) {
                $query->with('party', 'sale_order:id,voucher_no');
            }])
            ->where('voucher_no', $VoucherNo)
            ->where('type', 'DC')
            ->get();

        return Response::json(['data' => $data, 'status' => $status]);
    }
    }

    public function LoadPreviousData(Request $request)
    {
        // $deliveryChallan = DeliveryChallan::where('voucher_no', '<', $request->voucher_no)->where('type', 'DC')->max('voucher_no');
        // $VoucherNo = $deliveryChallan;
        //  $SaleOrder = DeliveryChallan::where('voucher_no', '=', $deliveryChallan)->where('type', 'DC')->get();
        // $SaleOrderNo = $SaleOrder[0]->sale_order_no;
        // if ($deliveryChallan) {

        //     $data = DeliveryChallanDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
        //         $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
        //                     $query->where('sale_order_no', '=', $SaleOrderNo);
        //                         $query->where('voucher_no', '<=', $VoucherNo);
        //                     }]);
        //         }])
        //         ->with(['delivery_challan' => function ($query) {
        //             $query->with('party', 'sale_order:id,voucher_no');
        //         }])
        //         ->where('voucher_no', $deliveryChallan)
        //         ->where('type', 'DC')
        //         ->get();

        //     return Response::json(['data' => $data]);
        // } else {
        //     return Response::json(['data' => '']);
        // }
// return $request;

        $status = '';
        $deliveryChallan = DeliveryChallan::where('voucher_no', '<', $request->voucher_no)
        ->where('type', 'DC')->max('voucher_no');
        $VoucherNo = $deliveryChallan;
         $SaleOrder = DeliveryChallan::where('voucher_no', '=', $deliveryChallan)->where('type', 'DC')->first();
        $SaleOrderNo = $SaleOrder->sale_order_no;
        $re = DeliveryChallanDetails::where('voucher_no', $deliveryChallan)->where('type', 'DC')->first();
        $products = CustomerProduct::with('product')
         ->where('product_id', $re->product_id)
         ->where('customer_id', $SaleOrder->party_id)
         ->count();
        if($products > 0) {
            $status = 0;
            // return $status;
           $data = DeliveryChallanDetails::
           with(['customer_product' => function($query) use ($SaleOrderNo, $VoucherNo){
            $query->with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
                $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                            $query->where('sale_order_no', '=', $SaleOrderNo);
                                $query->where('voucher_no', '<=', $VoucherNo);
                            }]);
            }]);
                }])
                // ->with('dc_details2') 
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party', 'sale_order:id,voucher_no');
                }])
                ->where('voucher_no', $VoucherNo)
                ->where('type', 'DC')
                ->get();

            return Response::json(['data' => $data, 'status' => $status]);
        } else {
            $status = 1;
            // return "chwla";
                $data = DeliveryChallanDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
                $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                            $query->where('sale_order_no', '=', $SaleOrderNo);
                                $query->where('voucher_no', '<=', $VoucherNo);
                            }]);
                }])
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party', 'sale_order:id,voucher_no');
                }])
                ->where('voucher_no', $deliveryChallan)
                ->where('type', 'DC')
                ->get();

            return Response::json(['data' => $data, 'status' => $status]);
        }
    }

    public function LoadNextData(Request $request)
    {
     $deliveryChallan = DeliveryChallan::where('voucher_no', '>', $request->voucher_no)->where('type', 'DC')->min('voucher_no');
    $VoucherNo = $deliveryChallan;

    $status = '';
     $SaleOrder = DeliveryChallan::where('voucher_no', '=', $deliveryChallan)->where('type', 'DC')->first();
     $SaleOrderNo = $SaleOrder->sale_order_no;
    $re = DeliveryChallanDetails::where('voucher_no', $deliveryChallan)->where('type', 'DC')->first();
     $products = CustomerProduct::with('product')
     ->where('product_id', $re->product_id)
     ->where('customer_id', $SaleOrder->party_id)
     ->count();
    if ($products > 0) {
         $status = 0;
       $data = DeliveryChallanDetails::
       with(['customer_product' => function($query) use ($SaleOrderNo, $VoucherNo){
        $query->with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
            $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                        $query->where('sale_order_no', '=', $SaleOrderNo);
                            $query->where('voucher_no', '<=', $VoucherNo);
                        }]);
        }]);
            }])
            // ->with('dc_details2') 
            ->with(['delivery_challan' => function ($query) {
                $query->with('party', 'sale_order:id,voucher_no');
            }])
            ->where('voucher_no', $VoucherNo)
            ->where('type', 'DC')
            ->get();

        return Response::json(['data' => $data, 'status' => $status]);
    } else {
        $status = 1;
            $data = DeliveryChallanDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
            $query->with(['dc_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                        $query->where('sale_order_no', '=', $SaleOrderNo);
                            $query->where('voucher_no', '<=', $VoucherNo);
                        }]);
            }])
            ->with(['delivery_challan' => function ($query) {
                $query->with('party', 'sale_order:id,voucher_no');
            }])
            ->where('voucher_no', $deliveryChallan)
            ->where('type', 'DC')
            ->get();

        return Response::json(['data' => $data, 'status' => $status]);
    }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DC ORDER')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
         $deliveryChallan = DeliveryChallan::where('voucher_no', $request->delete_voucher_no)
        ->where('type', 'DC')->first();
        if ($deliveryChallan) {
             DeliveryChallan::where('id', $deliveryChallan->id)->where('type', 'DC')->delete();
            DeliveryChallanDetails::where('challan_id', $deliveryChallan->id)->where('type', 'DC')->delete();
            GodownStockDetail::where('transaction_id', $deliveryChallan->id)->where('type', 'DC')->delete();

            return redirect()->back()->with('flash_message', 'Delivery Challan Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        //  return $request;
       $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DC ORDER')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            // return "not";
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        // File::cleanDirectory(base_path() . '/upload/delivery-challan');
        $voucher_no = $request->voucher_no;
        $status = '';
         $dc = DeliveryChallan::where('voucher_no', $voucher_no)->where('type', 'DC')->first();
        if ($dc) {

              $products = CustomerProduct::where('customer_id', $request->party_id)->count();

                // return $DeliveryChallan = DeliveryChallanDetails::with(['customer_product' => function ($query) {
                //     // $query->with('product:id,uom,product_code');
                // }])
                // ->where('voucher_no', $voucher_no)
                // ->orderBy('id', 'asc')
                // ->where('type','DC')
                // ->first();


                // $DeliveryChallan = DeliveryChallanDetails::where('voucher_no', $voucher_no)
                // ->orderBy('id', 'asc')
                // ->where('type','DC')
                // ->first();

            // if ($DeliveryChallan->customer_product) {
            if ($products > 0) {
                 $status = 0;
                $deliverychallanDetail = DeliveryChallanDetails::with('customer_product')
                ->with(['delivery_challan' => function ($qry) {
                    $qry->with('party:id,party_name,address', 'sale_order:id,voucher_no');
                }])
                    ->with('product:id,product_name,uom,code')
                    // ->where('voucher_no', $DeliveryChallan->voucher_no)
                    ->where('voucher_no', $voucher_no)
                    ->where('type', 'DC')
                    ->get();
                $pdf = PDF::loadView('delivery-challan.invoice', compact('deliverychallanDetail', 'status'));
                $fileName =  'sales-orders' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/delivery-challan/' . $fileName));
                return $fileName;
            } else {
                $status = 1;
                // $voucher_no = $request->voucher_no;
                $deliverychallanDetail = DeliveryChallanDetails::with(['delivery_challan' => function ($qry) {
                    $qry->with('party:id,party_name,address', 'sale_order:id,voucher_no');
                }])
                    ->with('product:id,product_name,uom,code')
                    // ->where('voucher_no', $DeliveryChallan->voucher_no)
                    ->where('voucher_no', $voucher_no)
                    ->where('type', 'DC')
                    ->get();
    
                $pdf = PDF::loadView('delivery-challan.invoice', compact('deliverychallanDetail', 'status'));
                $fileName =  'delivery-challan' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/delivery-challan/' . $fileName));
                return $fileName;
        }
        } else {
            return false;
        }
    }
    public function dataFetch(Request $request)
    {
        // return $request;
        $status = '';
        // return $re = SaleOrderDetails::where('sale_order_id', $request->sale_order_id)->first();
        $SaleOrderID = SaleOrder::where('id', $request->sale_order_id)->first();
        $products = CustomerProduct::with('product')->where('customer_id', $SaleOrderID->party_id)->count();
        if ($products > 0) {
            $status = 0;
            $saleorder = SaleOrderDetails::with(['customer_product' => function ($qury) {
                $qury->with('product:id,uom,product_name,product_code');
            }])
            ->with('dc_details2') 
                ->with('party:id,party_name,address')
                ->with('sale_order:id,po_date,po_no,voucher_date')
                ->where('sale_order_id', $request->sale_order_id)
                ->where('type', 'SALE ORDER')
                ->get();

            return Response::json(['data' => $saleorder, 'status' => $status]);
        } else {
            $status = 1;
            $SaleOrderNo = $request->sale_order_id;
            $saleorder = SaleOrderDetails::with(['product' => function($query) use ($SaleOrderNo){
                $query->with(['dc_details' => function($query) use ($SaleOrderNo){
                    $query->where('sale_order_no', $SaleOrderNo);
                }]);
            }])
                ->with('party:id,party_name,address')
                ->with('sale_order:id,po_date,po_no,voucher_date')
                ->where('sale_order_id', $request->sale_order_id)
                ->where('type', 'SALE ORDER')
                ->get();

            return Response::json(['data' => $saleorder, 'status' => $status]);
        }
    }
    public function Report(Request $request)
    {
        if ($request->ajax()) {
        //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        //     ->where('voucher_name', 'DELIVERY CHALLAN REPORT')
        //     ->where('right_name', 'ADD')
        //     ->first();
        // if (!$voucherRight) {
        //     return redirect()->back()->with('access_granted', 'Insufficientss Permission.');
        // }
          
            // return $fromDate = date('Y/m/d', strtotime($request->from_date));
            // $toDate = date('Y-d-m', strtotime($request->to_date));
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            // $toDate = $request->to_date;
            $party_id = $request->party_id;
            $DCType = $request->DcType;
           $reportType = $request->report_type;
            if ($reportType == 'summary') {
                if ($party_id == 0) {
                    if ($DCType == 0) {
                        // return "e3";
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        // 'Sum("delivery_challan_details.quantity")',
                        'delivery_challans.voucher_date',
                        'delivery_challans.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                    // ->where('delivery_challan_details.party_id', $party_id)
                    ->whereDate('delivery_challans.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challans.voucher_date', '<=', $toDate)
                    ->groupBy('delivery_challan_details.sale_order_no')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challans.voucher_date')
                    ->orderBy('delivery_challans.voucher_no')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }

                if ($party_id != 0) {
                    if ($DCType == 0) {
                        
                        //single party, no dc
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challans.voucher_date',
                        'delivery_challans.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                     ->where('delivery_challans.party_id', $party_id)
                    ->whereDate('delivery_challans.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challans.voucher_date', '<=', $toDate)
                    // ->groupBy('parties.party_name')
                    // ->orderBy('parties.id')
                    // ->orderBy('delivery_challan_details.voucher_date')

                    ->groupBy('delivery_challan_details.sale_order_no')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challans.voucher_date')
                    ->orderBy('delivery_challans.voucher_no')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }

                if ($party_id == 0) {
                    if ($DCType != 0) {
                       
                        //single DC, no Party
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challans.voucher_date',
                        'delivery_challans.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                    ->where('delivery_challan_details.type', $DCType)
                    ->whereDate('delivery_challans.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challans.voucher_date', '<=', $toDate)
                    ->groupBy('delivery_challan_details.sale_order_no')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challans.voucher_date')
                    ->orderBy('delivery_challans.voucher_no')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }

                if ($party_id != 0) {
                    if ($DCType != 0) {
                        // BOTH;
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challans.voucher_date',
                        'delivery_challans.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                    ->where('delivery_challans.party_id', $party_id)
                    ->where('delivery_challan_details.type', $DCType)
                    ->whereDate('delivery_challans.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challans.voucher_date', '<=', $toDate)
                    ->groupBy('delivery_challan_details.sale_order_no')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challans.voucher_date')
                    ->orderBy('delivery_challans.voucher_no')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }
            }

            if ($reportType == 'detailed') {
                if ($party_id == 0) {
                    if ($DCType == 0) {
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        // DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        // DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challan_details.quantity as quantity',
                        'delivery_challan_details.sale_qty as sale_qty',
                        'delivery_challan_details.voucher_date',
                        'delivery_challan_details.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                    // ->where('delivery_challan_details.party_id', $party_id)
                    ->whereDate('delivery_challan_details.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challan_details.voucher_date', '<=', $toDate)
                    // ->groupBy('parties.party_name')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challan_details.voucher_date')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }

                if ($party_id != 0) {
                    if ($DCType == 0) {
                        // return "0 DC";
                        //single party, no dc
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        // DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        // DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challan_details.quantity as quantity',
                        'delivery_challan_details.sale_qty as sale_qty',
                        'delivery_challan_details.voucher_date',
                        'delivery_challan_details.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                     ->where('delivery_challan_details.party_id', $party_id)
                    ->whereDate('delivery_challan_details.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challan_details.voucher_date', '<=', $toDate)
                    // ->groupBy('parties.party_name')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challan_details.voucher_date')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }

                if ($party_id == 0) {
                    if ($DCType != 0) {
                       
                        //single DC, no Party
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        // DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        // DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challan_details.quantity as quantity',
                        'delivery_challan_details.sale_qty as sale_qty',
                        'delivery_challan_details.voucher_date',
                        'delivery_challan_details.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                    ->where('delivery_challan_details.type', $DCType)
                    ->whereDate('delivery_challan_details.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challan_details.voucher_date', '<=', $toDate)
                    // ->groupBy('parties.party_name')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challan_details.voucher_date')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }

                if ($party_id != 0) {
                    if ($DCType != 0) {
                        // BOTH;
                return $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->join('delivery_challans', 'delivery_challans.id', '=', 'delivery_challan_details.challan_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'parties.party_name',
                        'delivery_challans.remarks',
                        'delivery_challan_details.sale_order_no',
                        'delivery_challans.type',
                        'delivery_challans.order_date',
                        'delivery_challans.vehicle_no',
                        'delivery_challans.transport_company',
                        'delivery_challans.driver_name',
                        'delivery_challans.builty_no',
                        'delivery_challans.driver_phoneno',
                        'delivery_challans.freight',
                        'delivery_challans.po_date',
                        'delivery_challan_details.po_no',
                        // DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        // DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challan_details.quantity as quantity',
                        'delivery_challan_details.sale_qty as sale_qty',
                        'delivery_challan_details.voucher_date',
                        'delivery_challan_details.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing',
                    )
                    ->where('delivery_challan_details.party_id', $party_id)
                    ->where('delivery_challan_details.type', $DCType)
                    ->whereDate('delivery_challan_details.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challan_details.voucher_date', '<=', $toDate)
                    // ->groupBy('parties.party_name')
                    // ->orderBy('parties.id')
                    ->orderBy('delivery_challan_details.voucher_date')
                    ->get();
                return response()->json(['data' => $summaryReport]);
                    }
                }
            }
            
            // if ($reportType == 'detail') {
            //     return "below";
            //     $detailReport = DeliveryChallanDetails::with('party:id,party_name', 'product:id,product_name,code,uom')
            //         ->where('party_id', $party_id)
            //         ->whereDate('voucher_date', '>=', $fromDate)
            //         ->whereDate('voucher_date', '<=', $toDate)
            //         ->get();
            //     return response()->json(['data' => $detailReport]);
            // }
        }
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            // ->where('parties.account_group_id', '1')
            // ->Orwhere('parties.account_group_id', '7')
            ->where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('All Parties', '0');
        $type = ['0' => 'All Delivery Challans', 'DCNonGST' => 'DC DEMAND', 'DC' => 'DC ORDER'];

        return view('delivery-challan.report.index', compact('customers', 'type'));
    }
}
