<?php

namespace App\Http\Controllers;

use App\Models\UOM;
use App\Models\Party;
use App\Models\Sales;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\SaleOrder;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\CustomerProduct;
use App\Models\SaleOrderDetails;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class SaleOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $codes = 1;
        $salesOrder = SaleOrder::where('type','SALE ORDER')
        // ->where('created_by', Auth::User()->id)
        ->OrderBy('id', 'desc')->first();
        if ($salesOrder) {
            $codes = $salesOrder->voucher_no + 1;
        }

        // $customers = Party::where('role', '=', 'Customer')
        //     ->where('type', 'Registered')
        //     ->OrderBy('party_name', 'asc')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party Name', '');
        $customers = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('role', '=', 'Customer')
            ->where('type', 'Registered')
            ->OrderBy('code', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

            $customers1 = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('role', '=', 'Customer')
            ->where('type', 'Registered')
            ->where('id', Auth::User()->party_id)
            ->OrderBy('code', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');
        // $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_code`, "_", `product_name`, "_", `uom`,"_", `product_price` ,"_", `packing` ,"_", `tax`) AS `id`, `product_name`'))
        //     ->OrderBy('id', 'asc')
        //     ->where('product_type', 'Finish')
        //     ->pluck('product_name', 'id')
        //     ->prepend('Select Product', '');

            // $products = Product::
            // // ->where('warehouse_id', 11)
            // where('product_type', 'Finish')
            // ->OrderBy('id', 'asc')
            // ->pluck('product_name', 'id')
            // ->prepend('Select Product', '');

            $products = Product::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('product_type', 'Finish')
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');


        $uoms = UOM::select(DB::raw('CONCAT(`id`, "_", `uom`) AS `id`, `uom`'))->OrderBy('id', 'asc')->pluck('uom', 'id')->prepend('GRAMS', '9_GRAMS');
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id');
        $payment_mode = array('' => 'Select Type', 'Cash' => 'Cash', 'Credit' => 'Credit');
        $shipment_term = array('' => 'Select Type', 'Supplier' => 'Supplier', 'Buyer' => 'Buyer');

        return view('sales-orders.index', compact('codes', 'customers', 'customers1', 'uoms', 'warehouse', 'payment_mode', 'shipment_term'));
    }

    public function getProduct(Request $request){
       $ProductID = $request->productID;
        $VoucherDate= $request->VoucherDate;
        $partyID= $request->partyID;
        
        $customerproduct = CustomerProduct::with('product:id,packing,tax,uom')
            ->where('customer_id', $request->partyID)->count();

        if ($customerproduct > 0) {
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

    public function store(Request $request)
    {
        // return $request->all();
        $Validator = Validator::make($request->all(), [
            'voucher_date' => 'required',
            'voucher_no' => 'required',
            'party_name' => 'required',
            'payment_mode' => 'required',
            'shipment_term' => 'required',
            'po_date' => 'required',
            'po_no' => 'required',
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
        if (!isset($request->price)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if ($request->update_voucher_id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALE ORDER')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $saleOrder = SaleOrder::where('id',$request->update_voucher_id)
            ->where('type','SALE ORDER')->first();

            $data = $request->all();
            $data['updated_by'] = Auth::User()->id;
            $saleOrder->update($data);

            SaleOrderDetails::where('sale_order_id', $saleOrder->id)->delete();


            $totalQty = 0;
            $totalpacking = 0;
            $totalOrderQty = 0;
            $totalSaleAmount = 0;

            $count = count($request->price);
            for ($i = 0; $i < $count; $i++) {
                $saleOrderDetails = new SaleOrderDetails();
                $saleOrderDetails->sale_order_id = $saleOrder->id;
                $saleOrderDetails->voucher_no = $saleOrder->voucher_no;
                $saleOrderDetails->party_id = $saleOrder->party_id;
                $saleOrderDetails->type ='SALE ORDER';
                $saleOrderDetails->voucher_date = $saleOrder->voucher_date;
                $saleOrderDetails->product_id = $request->product_id[$i];
                $saleOrderDetails->qty = $request->qty[$i];
                $saleOrderDetails->packing = $request->packing[$i];
                $saleOrderDetails->order_qty = $request->order_qty[$i];
                $saleOrderDetails->sale_rate = $request->price[$i];
                $saleOrderDetails->excl_value = $request->excl_value[$i];
                $saleOrderDetails->s_tax = $request->s_tax[$i];
                $saleOrderDetails->st_value = $request->st_value[$i];
                // $saleOrderDetails->sale_amount = $request->price[$i] * $request->qty[$i];
                $saleOrderDetails->sale_amount = $request->total[$i];
                $saleOrderDetails->delivery_date = $request->delivery_date[$i];
                $saleOrderDetails->remark = $request->remark[$i];
                $saleOrderDetails->save();

                $totalQty +=  $request->qty[$i];
                // $totalSaleAmount +=  $request->price[$i] * $request->qty[$i];
                $totalSaleAmount +=  $request->total[$i];
                $totalpacking +=  $request->packing[$i];
                $totalOrderQty +=  $request->order_qty[$i];
            }
            $saleOrder->total_qty = $totalQty;
            $saleOrder->total_packing = $totalpacking;
            $saleOrder->total_order_qty = $totalOrderQty;
            $saleOrder->total_sale_rate = $totalSaleAmount;
            $saleOrder->save();

            return redirect()->back()->with('flash_message', 'Sale Order Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'SALE ORDER')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return $request;
        $PartyCode = 1;
        $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('party_id', '=', $request->party_id)
            //  ->where('created_by', Auth::User()->id)
            ->orderBy('id', 'desc')
            ->first();
         if ($saleOrder) {
            $PartyCode = $saleOrder->party_voucher_no + 1;
        }
        // return $PartyCode;
            $codes = 1;
            $salesOrder = SaleOrder::where('type','SALE ORDER')->OrderBy('id', 'desc')->first();
            if ($salesOrder) {
                $codes = $salesOrder->voucher_no + 1;
            }
            $data = $request->all();
            $data['created_by'] = Auth::User()->id;
            $data['voucher_no'] = $codes;
            $data['party_voucher_no'] = $PartyCode;
            $data['status'] = 0;
            $data['type'] = 'SALE ORDER';
            $saleOrder = SaleOrder::create($data);

            $totalQty = 0;
            $totalpacking = 0;
            $totalOrderQty = 0;
            $totalSaleAmount = 0;

            

            $count = count($request->price);
            for ($i = 0; $i < $count; $i++) {
                $saleOrderDetails = new SaleOrderDetails();
                $saleOrderDetails->sale_order_id = $saleOrder->id;
                $saleOrderDetails->voucher_no = $saleOrder->voucher_no;
                $saleOrderDetails->party_id = $saleOrder->party_id;
                $saleOrderDetails->type ='SALE ORDER';
                $saleOrderDetails->voucher_date = $saleOrder->voucher_date;
                $saleOrderDetails->product_id = $request->product_id[$i];
                $saleOrderDetails->qty = $request->qty[$i];
                $saleOrderDetails->packing = $request->packing[$i];
                $saleOrderDetails->order_qty = $request->order_qty[$i];
                $saleOrderDetails->sale_rate = $request->price[$i];
                $saleOrderDetails->excl_value = $request->excl_value[$i];
                $saleOrderDetails->s_tax = $request->s_tax[$i];
                $saleOrderDetails->st_value = $request->st_value[$i];
                // $saleOrderDetails->sale_amount = $request->price[$i] * $request->qty[$i];
                // return $request->total[$i];
                $saleOrderDetails->sale_amount = $request->total[$i];
                $saleOrderDetails->delivery_date = $request->delivery_date[$i];
                $saleOrderDetails->remark = $request->remark[$i];
                $saleOrderDetails->save();

                $totalQty +=  $request->qty[$i];
                // $totalSaleAmount +=  $request->price[$i] * $request->qty[$i];
                $totalSaleAmount +=  $request->total[$i];
                $totalpacking +=  $request->packing[$i];
                $totalOrderQty +=  $request->order_qty[$i];
            }
            $saleOrder->total_qty = $totalQty;
            $saleOrder->total_packing = $totalpacking;
            $saleOrder->total_order_qty = $totalOrderQty;
            $saleOrder->total_sale_rate = $totalSaleAmount;
            $saleOrder->save();

            return redirect()->back()->with('flash_message', 'Sale Order Added Successfully!');
        }
    }

    public function editData(Request $request)
    {
        $currentUser = Auth::User()->role;
        if($currentUser == "Admin" || $currentUser == "Normal User"){
            $edit = SaleOrder::where('type','SALE ORDER')
         ->where('voucher_no', $request->voucher_no)
        // ->where('created_by', Auth::User()->id)
        ->first();
        }
        else{
            $edit = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', $request->voucher_no)
           ->where('created_by', Auth::User()->id)
           ->first();
        }
         
        $status = '';
        if ($edit) {
            // $edit = SaleOrderDetails::where('sale_order_id', $edit->id)
            //     ->with(['customer_product' => function ($query) {
            //         $query->with('product:id,uom');
            //     }])
            //     ->with(['sale_order' => function ($query) {
            //         $query->with('party');
            //     }])
            //     ->first();

            //   return  $data = SaleOrder::
            //     //  with(['sale_order_details' => function($query){
            //     //     $query->with('customer_product');
            //     // }])
            //     // ->
            //     where('voucher_no', $edit->voucher_no)
            //     ->where('type', 'SALE ORDER')
            //     ->first();
                 $CustomerID = $edit->party_id;
                 $customerProduct = CustomerProduct::where('customer_id', $CustomerID)->count();

            if ($customerProduct > 0) {
                $status = 0;

                if($currentUser == "Admin" || $currentUser == "Normal User"){
                    $edit1 = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', $request->voucher_no)
                    // ->where('created_by', Auth::User()->id)
                    ->first();
                }
                else{
                    $edit1 = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', $request->voucher_no)
                    ->where('created_by', Auth::User()->id)
                    ->first();
                }


                

                $edit2 = SaleOrderDetails::where('sale_order_id', $edit1->id)
                    ->with(['customer_product' => function ($query) {
                        $query->with('product:id,uom');
                    }])
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->get();
                    ////Customer Products Dropdown
                    // $getCustomer = SaleOrder::where('voucher_no', $edit1)->first();
                    $CustomerID = $edit1->party_id;
                     $products = CustomerProduct::with('product:id,packing,tax,uom')->where('customer_id', $CustomerID)->get();
                return Response::json(['data' => $edit2,'products' => $products, 'status' => $status]);
            } else {
               $status = 1;

               if($currentUser == "Admin" || $currentUser == "Normal User"){
                $edit3 = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', $request->voucher_no)
                // ->where('created_by', Auth::User()->id)
                ->first();
            }
            else{
                $edit3 = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', $request->voucher_no)
                ->where('created_by', Auth::User()->id)
                ->first();
            }

                // $edit3 = SaleOrder::where('type','SALE ORDER')
                // ->where('voucher_no', $request->voucher_no)
                // ->where('created_by', Auth::User()->id)
                // ->first();

                $edit4 = SaleOrderDetails::where('sale_order_id', $edit3->id)
                    ->with('product:id,uom,product_name,code')
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->get();
                // return Response::json(['data' => $edit4, 'status' => $status]);

                //Products Dropdown
                $products = Product::where('product_type', 'Finish')->OrderBy('id', 'asc')->get();
                return Response::json(['data' => $edit4, 'products' => $products, 'status' => $status]);
            }
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $currentUser = Auth::User()->role;
        if($currentUser == "Admin" || $currentUser == "Normal User"){
            $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', '<', $request->voucher_no)
           //  ->where('created_by', Auth::User()->id)
            ->max('voucher_no');
        }else{
            $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', '<', $request->voucher_no)
            ->where('created_by', Auth::User()->id)
            ->max('voucher_no');
        }
        //  return $saleOrder;
         
        $status = '';
        if ($saleOrder) {
            if($currentUser == "Admin" || $currentUser == "Normal User"){
                $data = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', $saleOrder)
                // ->where('created_by', Auth::User()->id)
                ->first();
            }
            else{
                $data = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', $saleOrder)
                ->where('created_by', Auth::User()->id)
                ->first();
            }
            // return $data;

             $CustomerID = $data->party_id;
             $customerProduct = CustomerProduct::where('customer_id', $CustomerID)->count();

            // if ($data->sale_order_details[0]->customer_product) {
            // if ($data->customer_product) {
            if ($customerProduct > 0) {
                $status = 0;

                // $saleOrder1 = SaleOrder::where('type','SALE ORDER')
                // ->where('voucher_no', '<', $request->voucher_no)
                // ->where('created_by', Auth::User()->id)
                // ->max('voucher_no');

                if($currentUser == "Admin" || $currentUser == "Normal User"){
                    $request->voucher_no;
                    $saleOrder1 = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', '<', $request->voucher_no)
                    // ->where('created_by', Auth::User()->id)
                    ->max('voucher_no');
                }
                else{
                    $saleOrder1 = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', '<', $request->voucher_no)
                ->where('created_by', Auth::User()->id)
                ->max('voucher_no');
                }
                // return $saleOrder1;

                $data1 = SaleOrderDetails::with(['customer_product' => function ($query) {
                    $query->with('product:id,uom');
                }])
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->where('voucher_no', $saleOrder1)
                    ->where('type','SALE ORDER')
                    ->get();
                    //Customer Products Dropdown
                    if($currentUser == "Admin" || $currentUser == "Normal User"){
                        $getCustomer = SaleOrder::where('type','SALE ORDER')
                        ->where('voucher_no', $saleOrder1)
                        ->first();
                    }else{
                        $getCustomer = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', $saleOrder1)
                    ->where('created_by', Auth::User()->id)
                    ->first();
                    }
                    
                     $CustomerID = $getCustomer->party_id;
                     $products = CustomerProduct::with('product:id,packing,tax,uom')->where('customer_id', $CustomerID)->get();
                return Response::json(['data' => $data1, 'products' => $products, 'status' => $status]);
            } else {
                $status = 1;

                if($currentUser == "Admin" || $currentUser == "Normal User"){
                    $saleOrder2 = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', '<', $request->voucher_no)
                    // ->where('created_by', Auth::User()->id)
                    ->max('voucher_no');
                }else{
                    $saleOrder2 = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', '<', $request->voucher_no)
                    ->where('created_by', Auth::User()->id)
                    ->max('voucher_no');
                }
                // return $saleOrder2;

                $data2 = SaleOrderDetails::with('product:id,uom,product_name,code')
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->where('voucher_no', $saleOrder2)
                    ->where('type','SALE ORDER')
                    ->get();
                    //Products Dropdown
                $products = Product::where('product_type', 'Finish')->OrderBy('id', 'asc')->get();
                return Response::json(['data' => $data2, 'products' => $products, 'status' => $status]);
            }
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        $currentUser = Auth::User()->role;
        if($currentUser == "Admin" || $currentUser == "Normal User"){
            $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', '>', $request->voucher_no)
           //  ->where('created_by', Auth::User()->id)
            ->min('voucher_no');
        }
        else{
            $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', '>', $request->voucher_no)
            ->where('created_by', Auth::User()->id)
            ->min('voucher_no');
        }
        //  return $saleOrder;
        $status = '';
        if ($saleOrder) {
// return $currentUser;
                if($currentUser == "Admin" || $currentUser == "Normal User"){
                   $data = SaleOrder::where('type', 'SALE ORDER')
                ->where('voucher_no', $saleOrder)
                // ->where('created_by', Auth::User()->id)
                ->first();
                }
                else{
                    $data = SaleOrder::where('type', 'SALE ORDER')
                ->where('voucher_no', $saleOrder)
                ->where('created_by', Auth::User()->id)
                ->first();
                }

                // return $data;
                 $CustomerID = $data->party_id;
                 $customerProduct = CustomerProduct::where('customer_id', $CustomerID)->count();

            if ($customerProduct > 0) {
                $status = 0;

                

                if($currentUser == "Admin" || $currentUser == "Normal User"){
                    $saleOrder1 = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', '>', $request->voucher_no)
                // ->where('created_by', Auth::User()->id)
                ->min('voucher_no');
                }
                else{
                    $saleOrder1 = SaleOrder::where('type','SALE ORDER')
                ->where('voucher_no', '>', $request->voucher_no)
                ->where('created_by', Auth::User()->id)
                ->min('voucher_no');
                }



                $data1 = SaleOrderDetails::with(['customer_product' => function ($query) {
                    $query->with('product:id,uom');
                }])
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->where('voucher_no', $saleOrder1)
                    ->where('type','SALE ORDER')
                    ->get();
                    //Customer Products Dropdown
                    

                    if($currentUser == "Admin" || $currentUser == "Normal User"){
                    //     $saleOrder1 = SaleOrder::where('type','SALE ORDER')
                    // ->where('voucher_no', '>', $request->voucher_no)
                    // ->where('created_by', Auth::User()->id)
                    // ->min('voucher_no');

                    $getCustomer = SaleOrder::where('type','SALE ORDER')
                    // ->where('created_by', Auth::User()->id)
                    ->where('voucher_no', $saleOrder1)
                    ->first();
                    }
                    else{
                        $getCustomer = SaleOrder::where('type','SALE ORDER')
                    ->where('created_by', Auth::User()->id)
                    ->where('voucher_no', $saleOrder1)
                    ->first();
                    }


                     $CustomerID = $getCustomer->party_id;
                     $products = CustomerProduct::with('product:id,packing,tax,uom')->where('customer_id', $CustomerID)->get();
                return Response::json(['data' => $data1,'products' => $products, 'status' => $status]);
            } else {
                $status = 1;

                


                if($currentUser == "Admin" || $currentUser == "Normal User"){
                    $saleOrder2 = SaleOrder::where('type','SALE ORDER')
                    ->where('voucher_no', '>', $request->voucher_no)
                    // ->where('created_by', Auth::User()->id)
                    ->min('voucher_no');
                    }
                    else{
                        $saleOrder2 = SaleOrder::where('type','SALE ORDER')
                        ->where('voucher_no', '>', $request->voucher_no)
                        ->where('created_by', Auth::User()->id)
                        ->min('voucher_no');
                    }

                    // return $saleOrder2;

                $data2 = SaleOrderDetails::with('product:id,uom,product_name,code')
                    ->with(['sale_order' => function ($query) {
                        $query->with('party');
                    }])
                    ->where('voucher_no', $saleOrder2)
                    ->where('type','SALE ORDER')
                    ->get();
                    //Products Dropdown
                    $products = Product::where('product_type', 'Finish')->OrderBy('id', 'asc')->get();
                    return Response::json(['data' => $data2, 'products' => $products, 'status' => $status]);
            }
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALE ORDER')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        $currentUser = Auth::User()->role;
        if($currentUser == "Admin" || $currentUser == "Normal User"){
            $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', $request->delete_voucher_no)
            // ->where('created_by', Auth::User()->id)
            ->first();
        }else{
             $saleOrder = SaleOrder::where('type','SALE ORDER')
            ->where('voucher_no', $request->delete_voucher_no)
            ->where('created_by', Auth::User()->id)
            ->first();
        }
// return $saleOrder;
        
        if($saleOrder) {
            if($currentUser == "Admin" || $currentUser == "Normal User"){
            SaleOrder::where('id', $saleOrder->id)->delete();
            SaleOrderDetails::where('sale_order_id', $saleOrder->id)
            ->where('type','SALE ORDER')->delete();
            }else{
                SaleOrder::where('id', $saleOrder->id)->delete();
                SaleOrderDetails::where('sale_order_id', $saleOrder->id)
                ->where('created_by', Auth::User()->id)
                ->where('type','SALE ORDER')->delete(); 
            }

            return redirect()->back()->with('flash_message','Sale Order Deleted Successfully!');
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
                $summaryReport = SaleOrder::with('party:id,party_name')
                    ->where('party_id', $party_id)
                    ->whereDate('voucher_date', '>=', $fromDate)
                    ->whereDate('voucher_date', '<=', $toDate)
                    ->get();
                return response()->json(['data' => $summaryReport]);
            } else if ($reportType == 'detail') {
                $detailReport = SaleOrderDetails::with('party:id,party_name', 'product:id,product_name,product_code,uom')
                    ->where('party_id', $party_id)
                    ->whereDate('voucher_date', '>=', $fromDate)
                    ->whereDate('voucher_date', '<=', $toDate)
                    ->get();
                return response()->json(['data' => $detailReport]);
            }
        }
        $party = Party::where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');
        return view('sales-orders.report', compact('party'));
    }
    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'SALE ORDER')
            ->where('right_name', 'PRINT')
            ->first();

        File::cleanDirectory(base_path() . '/upload/sales-orders');
        $voucher_no = $request->voucher_no;

        // $currentUser = Auth::User()->role;
        // if($currentUser == "Admin"){
        //     $edit = SaleOrder::where('type','SALE ORDER')
        //  ->where('voucher_no', $request->voucher_no)
        // // ->where('created_by', Auth::User()->id)
        // ->first();
        // }
        // else{
        //     $edit = SaleOrder::where('type','SALE ORDER')
        //     ->where('voucher_no', $request->voucher_no)
        //    ->where('created_by', Auth::User()->id)
        //    ->first();
        // }

        $status = '';
         $saleorder = SaleOrder::where('type','SALE ORDER')->where('voucher_no', $voucher_no)
        // ->where('created_by', Auth::User()->id)
        ->first();

        // $CustomerID = $edit->party_id;
        // $customerProduct = CustomerProduct::where('customer_id', $CustomerID)->count();
        
        if ($saleorder) {
            // $saleorderDetails = SaleOrderDetails::with(['sale_order' => function ($qry) {
            //     $qry->with('party:id,party_name,address', 'user:id,name');
            // }])
            //     ->with(['customer_product' => function ($query) {
            //         $query->with('product:id,uom,product_code');
            //     }])
            //     ->where('voucher_no', $voucher_no)
            //     ->orderBy('id', 'asc')
            //     ->where('type','SALE ORDER')
            //     ->first();

            $customerProduct = CustomerProduct::where('customer_id', $saleorder->party_id)->first();
            if ($customerProduct) {
                $status = 0;
                $voucher_no = $request->voucher_no;
                $saleorderDetails = SaleOrderDetails::with(['sale_order' => function ($qry) {
                    $qry->with('party:id,party_name,address', 'user:id,name');
                }])
                    ->with(['customer_product' => function ($query) {
                        $query->with('product:id,uom,code');
                    }])
                    ->where('voucher_no', $voucher_no)
                    ->where('type','SALE ORDER')
                    ->orderBy('id', 'asc')
                    ->get();
                $pdf = PDF::loadView('sales-orders.invoice', compact('saleorderDetails', 'status'))->setPaper('a4', 'landscape');
                $fileName =  'sales-orders' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/sales-orders/' . $fileName));
                return $fileName;
            } else {
                $status = 1;
                $voucher_no = $request->voucher_no;
                $saleorderr = SaleOrder::where('type','SALE ORDER')->where('voucher_no', $voucher_no)
                // ->where('created_by', Auth::User()->id)
                ->first();
                if($saleorderr){
                $saleorderDetails = SaleOrderDetails::with(['sale_order' => function ($qry) {
                    $qry->with('party:id,party_name,address', 'user:id,name');
                }])
                    ->with('product:id,uom,code,product_name')
                    ->where('voucher_no', $voucher_no)
                    ->where('type','SALE ORDER')
                    ->orderBy('id', 'asc')
                    ->get();
                $pdf = PDF::loadView('sales-orders.invoice', compact('saleorderDetails', 'status'))->setPaper('a4', 'landscape');
                $fileName =  'sales-orders' . $voucher_no . '.pdf';
                $pdf->save(base_path('upload/sales-orders/' . $fileName));
                return $fileName;
            }
        }
        } else {
            return false;
        }
    }
    // public function getCustomerProduct(Request $request)
    // {
    //     $products = array();
    //     $company = false;
    //     $customerproduct = CustomerProduct::where('customer_id', $request->cat_id)->count();
    //     if ($customerproduct > 0) {
    //         $products = CustomerProduct::with('product:id,packing,tax,uom,product_price')->where('customer_id', $request->cat_id)->get();
    //     } else {
    //         $company = true;
    //         $products = Product::with('rate_list_products')->where('product_type', 'Finish')->OrderBy('id', 'asc')->get();
    //     }

    //     return json_encode(['products' => $products, 'company' => $company]);
    // }

    public function getCustomerProduct(Request $request)
    {
        // return "enter2";
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
