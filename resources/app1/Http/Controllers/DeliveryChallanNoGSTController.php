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
use App\Models\GodownStock;
use App\Models\GodownStockDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\DeliveryChallanDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as DataTables;

class DeliveryChallanNoGSTController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'DC DEMAND')
        // ->where('voucher_name', 'DELIVERY CHALLAN NON GST')
        ->where('right_name', 'ADD')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        $codes = 1;
        // return $deliveryChallan = DeliveryChallan::where('type','DCNonGST')->OrderBy('id', 'desc')->first();
        $deliveryChallan = DeliveryChallan::where('type','DCNonGST')->max('voucher_no');
        if ($deliveryChallan) {
            $codes = $deliveryChallan + 1;
        }
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `product_name`, "_", `uom`,"_", `packing`,"_", `product_price`) AS `id`,  `product_name`'))
            ->where('warehouse_id', 11)
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
        $customers = Party::select(
            DB::raw('
            CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`
             '))
            ->where('role', '=', 'Customer')
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');

        // $salerOrderNo = SaleOrder::where('type','SALE DEMAND')->where('status', 0)->orderBy('id', 'asc')->pluck('voucher_no', 'id')->prepend('Sale Demand No', '');
        $salerOrderNo = DB::table('sale_orders')
            ->join('parties', 'parties.id', '=', 'sale_orders.party_id')
            ->select(DB::raw("sale_orders.id,CONCAT(sale_orders.voucher_no, '-', parties.party_name) AS  voucher_no")) // Concatenating the columns
            ->where('sale_orders.type', 'SALE DEMAND')
            ->where('sale_orders.status', 0)->orderBy('sale_orders.id', 'asc')
            ->pluck('voucher_no','sale_orders.id')
            ->prepend('Sale Demand No', '');
        return view('delivery-challan-no-gst.index', compact('codes', 'customers', 'products', 'salerOrderNo'));
    }

    public function LoadSaleDemands(Request $request)
    {
        //  return $request->partyid;
        if ($request->ajax()) {

             $SaleOrderNo = $request->partyid;

                 $stock1 = SaleOrderDetails::
                join('products', 'products.id', '=', 'sale_order_details.product_id')
                ->join('sale_orders', 'sale_orders.id', '=', 'sale_order_details.sale_order_id')
                ->with('dc_details2') 
                ->select(
                        'sale_order_details.id',
                        'sale_order_details.voucher_no',
                        'sale_order_details.voucher_date',
                        'sale_orders.po_no',
                        'sale_orders.po_date',
                        'products.product_name',
                        'sale_order_details.order_qty',
                        )
                        //->
                        ->where('sale_order_details.type', 'SALE DEMAND')
                        ->where('sale_order_details.status', 0)
                        ->where('sale_order_details.party_id', $request->partyid)
                        // ->orderBy('sale_order_details.id')
                        ->get();
                        
                        $listarray = array();
                        $newData = array();
                        $voucherNo = 0;
                        
                         foreach($stock1 as $data){
                            $totalqty = 0;
                            foreach($data->dc_details2 as $value){
                                $totalqty = $totalqty + $value->sale_qty;
                                 
                            }
                                $newData['id']=$data->id;
                                $newData['voucher_no']=$data->voucher_no;
                                $newData['voucher_date']=$data->voucher_date;
                                $newData['product_name']=$data->product_name;
                                $newData['po_no']=$data->po_no;
                                $newData['po_date']=$data->po_date;
                                $newData['order_qty']=$data->order_qty;
                                $newData['sale_qty']=$totalqty;
                             
                             $listarray[] = $newData;
                         }

            return DataTables::of($listarray)
           
                ->addIndexColumn()
                ->editColumn('selectValue', function ($row) {
                    $idvalue = $row['id'];
                    $btn = "<input type='checkbox' name='checkbox_consignment[]' class='checkbox_type' value='$idvalue' id='$idvalue'/><label for='$idvalue'></label>";
                    return $btn;
                })
                ->editColumn('voucher_no', function ($row) {
                    // $btn = "<a href='javascript:void(0)' class='select-grnno text-primary' id=" . $row->voucher_no . '_' . $row->voucher_no . "><u>" . $row->voucher_no . "</u></a>";
                    return $row['voucher_no'];
                    // return $btn;
                })
                ->editColumn('date', function ($row) {
                    return date('d/m/Y', strtotime($row['voucher_date']));
                    // return json_encode($row);
                })
                ->editColumn('product_id', function ($row) {
                    return $row['product_name'];
                })
                ->editColumn('po_no', function ($row) {
                    return $row['po_no'];
                })
                ->editColumn('po_date', function ($row) {
                    return date('d/m/Y', strtotime($row['po_date']));
                })
                ->editColumn('order_qty', function ($row) {
                    return $row['order_qty'];
                })
                ->editColumn('balance', function ($row) {
                    return $row['order_qty'] - $row['sale_qty'];
                })
                ->rawColumns(['selectValue'])
                ->make(true);
        }
    }
    
    public function LoadData(Request $request)
    {
        // return $request;
        // $stock = SaleOrderDetails::find($request->OrderDetailId);
        // if ($stock) {
        //     $stockDetails = SaleOrderDetails::with('product:id,product_name,uom,uom_id,product_cost,code')
        //         ->with(['sale_order' => function ($query) {
        //             $query->with('party:id,party_name,address');
        //         }])
        //         ->whereType('SALE DEMAND')
        //         ->where('id', $request->OrderDetailId)
        //         ->get();
        //     return Response::json(['data' => $stockDetails]);
        // } else {
        //     return Response::json(['data' => '']);
        // }
        // abort(500);


        $count = count($request->OrderDetailId);
        $status = 1;
         $SaleOrderNo = $request->OrderDetailId;
         $saleorder = SaleOrderDetails::with('product')
    //      with(['product' => function($query) use ($SaleOrderNo){
    //     //    $query->with(['dc_details' => function($query) use ($SaleOrderNo){
    //     //        $query->where('order_detail_id', $SaleOrderNo);
    //     //    }]);
    //    }])
    //    ->
            ->with('dc_details2') 
           ->with('party:id,party_name,address')
           ->with('sale_order:id,po_date,po_no')
           ->whereIn('id', $request->OrderDetailId)
           ->where('type', 'SALE DEMAND')
           ->get();

       return Response::json(['data' => $saleorder, 'status' => $status]);
    }

    public function store(Request $request)
    {
            // return $request->all();
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
        // return $request;
        if ($request->update_voucher_id != null) {
            // return "update0";
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DC DEMAND')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return $request;
            //  return $saleOrder = SaleOrder::where('voucher_no', $request->sale_order_no_edit)
            //  ->where('type','SALE DEMAND')->first();
            //  return $request;
             //return $request->sale_order_no_edit();
             $deliveryChallan = DeliveryChallan::where('id',$request->update_voucher_id)->where('type','DCNonGST')->first();
            //  return $deliveryChallan->sale_qty;
            $deliveryChallan->update($request->all());
            // return "dd";
            // $deliveryChallan->sale_order_no = $saleOrder['id'];
            $deliveryChallan->updated_by = Auth::user()->id;
            $deliveryChallan->save();
            //  $gdstock = GodownStock::where('inward_gatepass_id', $request->update_voucher_id)->where('type','DCNonGST')->first();
            // // $data1 = $request->all();
            // $data1['voucher_no'] = $deliveryChallan->voucher_no;
            // $data1['inward_gatepass_id'] = $deliveryChallan->id;
            // $data1['date'] = $request->voucher_date;
            // $data1['type'] = 'DCNonGST';
            // $data1['from_warehouse_id'] = Auth::User()->warehouse_id;
            // $data1['party_id'] = $deliveryChallan->party_id;
            // $data1['remarks'] = $deliveryChallan->remarks;
            // $data1['status'] = 0;
            // $data1['created_by'] = Auth::user()->id;
            // $gdstock->update($data1);
            // return $gdstock;
            // $gdstock = GodownStock::create($data1);
            DeliveryChallanDetails::where('challan_id', $deliveryChallan->id)->where('type','DCNonGST')->delete();
            GodownStockDetail::where('transaction_id', $deliveryChallan->id)->where('type','DCNonGST')->delete();
            $totalQty = 0;
            $totalsale_qty = 0; $grandRemaining = 0; $totalvalue=0;

            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $deliveryChallanDetails = new DeliveryChallanDetails();
                // return 3;
                $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->product_id = $request->product_id[$i];
                $deliveryChallanDetails->voucher_no = $deliveryChallan->voucher_no;
                $deliveryChallanDetails->sale_order_no = $request->sale_order_no1[$i];
                $deliveryChallanDetails->order_detail_id = $request->order_detail_id[$i];
                $deliveryChallanDetails->po_no = $request->po_no[$i];
                $deliveryChallanDetails->party_id = $deliveryChallan->party_id;
                $deliveryChallanDetails->type = 'DCNonGST';
                $deliveryChallanDetails->voucher_date = $request->voucher_date;
                $deliveryChallanDetails->warehouse_id = $deliveryChallan->warehouse_id;
                $deliveryChallanDetails->demandqty = $request->demandqty[$i];
                $deliveryChallanDetails->demandPCS = $request->demandPCS[$i];
                $deliveryChallanDetails->quantity = $request->qty[$i];
                $deliveryChallanDetails->packing = $request->packing[$i];
                // $deliveryChallanDetails->sale_qty = $totalvalue;
                $deliveryChallanDetails->sale_qty = $request->sale_qty[$i];
                // $deliveryChallanDetails->sale_qty = $totalvalue;
                // $deliveryChallanDetails->remaining_qty = $request->sale_qty[$i];
                $deliveryChallanDetails->comments = $request->comment[$i];
                $deliveryChallanDetails->sale_rate = $request->sale_rate[$i];
                $deliveryChallanDetails->created_by = $deliveryChallan->created_by;
                $deliveryChallanDetails->updated_by = Auth::User()->id;
                $deliveryChallanDetails->save();


                
                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $deliveryChallan->voucher_no;
                // $godownstockDetails->transaction_id = $gdstock->id;
                $godownstockDetails->transaction_id = $deliveryChallan->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date = $deliveryChallan->voucher_date;
                $godownstockDetails->type = 'DCNonGST';
                $godownstockDetails->warehouse_id = $deliveryChallan->warehouse_id;
                $godownstockDetails->party_id = $request->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_in = 0;
                $godownstockDetails->qty_out = $request->sale_qty[$i];
                $godownstockDetails->demand_qty = $request->demandPCS[$i];
                $godownstockDetails->remarks = $request->comment[$i];
                $godownstockDetails->updated_by = Auth::User()->id;
                $godownstockDetails->sale_rate = $request->sale_rate[$i];
                $godownstockDetails->save();

                
            }
            // return "ddd";

            return redirect()->back()->with('flash_message', 'Delivery Challan Non Gst Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DC DEMAND')
                // ->where('voucher_name', 'DELIVERY CHALLAN NON GST')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            
            $codes = 1;
            // $deliveryChallan = DeliveryChallan::where('type','DCNonGST')->OrderBy('id', 'desc')->first();
            $deliveryChallan = DeliveryChallan::where('type','DCNonGST')->max('voucher_no');
            if ($deliveryChallan) {
                $codes = $deliveryChallan + 1;
            }
           $data = $request->all();
            $data['voucher_no'] = $codes;
            $data['type'] = 'DCNonGST';
            $data['status'] = 0;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['created_by'] = Auth::user()->id;
            $data['po_no'] = 0;
            // $newdata = json_encode($data);
            // return "d";
            // return $data;
            $deliveryChallan = DeliveryChallan::create($data);
            // return $godown = GodownStock::create($data);
            // return $data;
            // $gdstock = new GodownStock();
            // $gdstock->voucher_no = $deliveryChallan->voucher_no;
            // $gdstock->inward_gatepass_id = $deliveryChallan->id;
            // $gdstock->date = $request->voucher_date;
            // $gdstock->type = 'DCNonGST';
            // $gdstock->from_warehouse_id = Auth::User()->warehouse_id;
            // $gdstock->party_id = $deliveryChallan->party_id;
            // $gdstock->status = 0;
            // $gdstock->created_by = Auth::User()->id;

            // $data1 = $request->all();
            // $data1['voucher_no'] = $deliveryChallan->voucher_no;
            // $data1['inward_gatepass_id'] = $deliveryChallan->id;
            // $data1['date'] = $request->voucher_date;
            // $data1['type'] = 'DCNonGST';
            // $data1['from_warehouse_id'] = Auth::User()->warehouse_id;
            // $data1['party_id'] = $deliveryChallan->party_id;
            // $data1['status'] = 0;
            // $data1['created_by'] = Auth::user()->id;
            // $gdstock = GodownStock::create($data1);
            // $gdstock->save();
            // return $gdstock->id;
        //     $deliveryChallan->created_by = Auth::user()->id;
        //     $deliveryChallan->updated_by = Auth::user()->id;


        //    return "ddd";
            $totalQty = 0;
            $totalsale_qty = 0; $grandRemaining=0;
            // $saleorder = Saleorder::where('id',$deliveryChallan->sale_order_no)->first();
            // return $request;
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {

            //    $orderDetail = SaleOrderDetails::where('id', $request->order_detail_id[$i])->first();
            //    $orderDetail->status = 1;
            //    $orderDetail->save();
               
              

                
                $deliveryChallanDetails = new DeliveryChallanDetails();
                // $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->challan_id = $deliveryChallan->id;
                $deliveryChallanDetails->product_id = $request->product_id[$i];
                $deliveryChallanDetails->voucher_no = $deliveryChallan->voucher_no;
                $deliveryChallanDetails->sale_order_no = $request->sale_order_no1[$i];
                $deliveryChallanDetails->order_detail_id = $request->order_detail_id[$i];
                $deliveryChallanDetails->po_no = $request->po_no[$i];
                $deliveryChallanDetails->party_id = $deliveryChallan->party_id;
                $deliveryChallanDetails->type = 'DCNonGST';
                $deliveryChallanDetails->voucher_date = $request->voucher_date;
                $deliveryChallanDetails->warehouse_id = Auth::User()->warehouse_id;
                $deliveryChallanDetails->demandqty = $request->demandqty[$i];
                $deliveryChallanDetails->demandPCS = $request->demandPCS[$i];
                $deliveryChallanDetails->quantity = $request->qty[$i];
                $deliveryChallanDetails->packing = $request->packing[$i];
                $deliveryChallanDetails->sale_qty = $request->sale_qty[$i];
                $deliveryChallanDetails->comments = $request->comment[$i];
                $deliveryChallanDetails->sale_rate = $request->sale_rate[$i];
                $deliveryChallanDetails->created_by = Auth::User()->id;
                $deliveryChallanDetails->save();
            
                // Remaining Qty in Sale Orders Detail Table
                
               

                $godownstockDetails = new GodownStockDetail();
                $godownstockDetails->voucher_no = $deliveryChallan->voucher_no;
                $godownstockDetails->transaction_id = $deliveryChallan->id;
                // $godownstockDetails->transaction_id = $gdstock->id;
                // $godownstockDetails->inward_gatepass_id =1;
                $godownstockDetails->date = $deliveryChallan->voucher_date;
                $godownstockDetails->type = 'DCNonGST';
                $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
                $godownstockDetails->party_id = $request->party_id;
                $godownstockDetails->product_id = $request->product_id[$i];
                $godownstockDetails->qty_in = 0;
                $godownstockDetails->qty_out = $request->sale_qty[$i];
                $godownstockDetails->demand_qty = $request->demandPCS[$i];
                $godownstockDetails->remarks = $request->comment[$i];
                $godownstockDetails->created_by = Auth::User()->id;
                $godownstockDetails->sale_rate = $request->sale_rate[$i];
                $godownstockDetails->save(); 
            }
            return redirect()->back()->with('flash_message', 'Delivery Challan Non Gst Added Successfully!');
        }
        return redirect()->back()->with('error_message', 'Something went wrong!!!');
    }

    public function editData(Request $request)
    {
        // $VoucherNo = $request->voucher_no;
        
         $edit = DeliveryChallan::where('voucher_no', $request->voucher_no)->where('type', 'DCNonGST')->first();
         //voucher no 5
        $VoucherNo = $edit->voucher_no;
        $SaleOrder = DeliveryChallan::where('voucher_no', '=', $edit->voucher_no)->where('type', 'DCNonGST')->get();
        //79
        // $SaleOrderNo = $SaleOrder[0]->sale_order_no;
        if ($edit) {
                // $data = DeliveryChallanDetails::with(['product' => function($query) use ($VoucherNo){
                //     $query->with(['dc_details' => function($query) use ($VoucherNo){
                //                 // $query->where('sale_order_no', '=', $SaleOrderNo);
                //                 // $query->where('voucher_no', '<=', $VoucherNo);
                //                 $query->where('voucher_no', '=', $VoucherNo);
                //                 $query->where('type', '=', "DCNonGST");
                //                 $query->groupby('order_detail_id');
                //              }]);
                //  }])
                
                //     ->with(['delivery_challan' => function ($query) {
                //         $query->with('party', 'sale_order:id,voucher_no');
                //     }])
                //     ->where('voucher_no', $VoucherNo)
                //     ->where('type', 'DCNonGST')
                //     ->get();

                $data = DeliveryChallanDetails::with('product')->with(['saleorderdetail' => function($query){
                    $query->with('dc_details2');
                }])
                    ->with(['delivery_challan' => function ($query) {
                        $query->with('party', 'sale_order:id,voucher_no');
                    }])
                    ->where('voucher_no', $VoucherNo)
                    ->where('type', 'DCNonGST')
                    ->get();

                    

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        //   $VoucherNo = $request->voucher_no;
          $deliveryChallan = DeliveryChallan::where('voucher_no', '<', $request->voucher_no)->where('type', 'DCNonGST')->max('voucher_no');
            $VoucherNo = $deliveryChallan;
           $SaleOrder = DeliveryChallan::where('voucher_no', '=', $deliveryChallan)->where('type', 'DCNonGST')->get();
            $SaleOrderNo = $SaleOrder[0]->sale_order_no;
        if ($deliveryChallan) {
            //  $data = DeliveryChallanDetails::with(['product' => function($query) use ($VoucherNo){
            //     $query->with(['dc_details' => function($query) use ($VoucherNo){
            //                 // $query->where('sale_order_no', '=', $SaleOrderNo);
            //                 //  $query->where('voucher_no', '<=', $VoucherNo);
            //                  $query->where('voucher_no', '=', $VoucherNo);
            //                  $query->where('type', '=', "DCNonGST");
            //              }]);
            //  }])
            //     ->with(['delivery_challan' => function ($query) {
            //         $query->with('party', 'sale_order:id,voucher_no');
            //     }])
            //     ->where('voucher_no', $deliveryChallan)
            //     ->where('type', 'DCNonGST')
            //     ->get();

            $data = DeliveryChallanDetails::with('product')->with(['saleorderdetail' => function($query){
                $query->with('dc_details2');
            }])
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party', 'sale_order:id,voucher_no');
                }])
                ->where('voucher_no', $VoucherNo)
                ->where('type', 'DCNonGST')
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadNextData(Request $request)
    {
        
        $deliveryChallan = DeliveryChallan::where('voucher_no', '>', $request->voucher_no)->where('type', 'DCNonGST')->min('voucher_no');
         $VoucherNo = $deliveryChallan;
        //5
        $SaleOrder = DeliveryChallan::where('voucher_no', '=', $deliveryChallan)->where('type', 'DCNonGST')->get();
        //  $SaleOrderNo = $SaleOrder[0]->sale_order_no;
          //79   
        if ($deliveryChallan) {
            // $data = DeliveryChallanDetails::with('product:id,product_name,uom')
            //     ->with(['delivery_challan' => function ($query) {
            //         $query->with('party', 'sale_order:id,voucher_no');
            //     }])
            //     ->where('voucher_no', $deliveryChallan)
            //     ->where('type', 'DCNonGST')
            //     ->get();

            $data = DeliveryChallanDetails::with('product')->with(['saleorderdetail' => function($query){
                $query->with('dc_details2');
            }])
                ->with(['delivery_challan' => function ($query) {
                    $query->with('party', 'sale_order:id,voucher_no');
                }])
                ->where('voucher_no', $VoucherNo)
                ->where('type', 'DCNonGST')
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function DeleteVoucher(Request $request)
    {
        // return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DC DEMAND')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $deliveryChallan= DeliveryChallan::where('voucher_no', $request->delete_voucher_no)->where('type', 'DCNonGST')->first();
            // $gdstock= GodownStock::where('voucher_no', $request->delete_voucher_no)->where('type', 'DCNonGST')->first('id');
        if ($deliveryChallan) {
            // DeliveryChallan::findOrFail($deliveryChallan->id)->where('type', 'DCNonGST')->delete();
            DeliveryChallan::where('id', $deliveryChallan->id)->where('type', 'DCNonGST')->delete();
            DeliveryChallanDetails::where('challan_id', $deliveryChallan->id)->where('type', 'DCNonGST')->delete();
            GodownStockDetail::where('transaction_id', $deliveryChallan->id)->where('type', 'DCNonGST')->delete();
            // GodownStockDetail::where('transaction_id', $gdstock->id)->where('type', 'DCNonGST')->delete();
            // GodownStock::where('inward_gatepass_id', $deliveryChallan->id)->where('type', 'DCNonGST')->delete();

            return redirect()->back()->with('flash_message', 'Delivery Challan Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
         //return "dds";
        // $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        //     ->where('voucher_name', 'DELIVERY CHALLAN NON GST')
        //     ->where('right_name', 'PRINT')
        //     ->first();
        // if (!$voucherRight) {
        //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        // }
        // File::cleanDirectory(base_path() . '/upload/delivery-challan-no-gst');


        // $voucher_no = $request->voucher_no;
        // $DeliveryChallan = DeliveryChallan::where('voucher_no', $voucher_no)->where('type', 'DCNonGST')->first();
        
    //    $deliveryChallan = DeliveryChallan::where('voucher_no', '=', $request->voucher_no)->where('type', 'DCNonGST')->first();
    //     $VoucherNo = $deliveryChallan;
    //    //5
    //    $SaleOrder = DeliveryChallan::where('voucher_no', '=', $deliveryChallan)->where('type', 'DCNonGST')->get();
    //     $SaleOrderNo = $SaleOrder[0]->sale_order_no;

         $DeliveryChallan = DeliveryChallan::where('voucher_no', $request->voucher_no)->where('type', 'DCNonGST')->first();
         //voucher no 5
          $VoucherNo = $DeliveryChallan->voucher_no;
           $SaleOrder = DeliveryChallan::where('voucher_no', '=', $DeliveryChallan->voucher_no)->where('type', 'DCNonGST')->get();
        //79
        // return $SaleOrderNo = $SaleOrder[0]->sale_order_no;
        if ($DeliveryChallan) {
            // $deliverychallanDetail = DeliveryChallanDetails::with(['delivery_challan' => function ($qry) {
            //     $qry->with('party:id,party_name,address', 'sale_order:id,voucher_no');
            // }])
            //     ->with('product:id,product_name,uom')
            //     ->where('voucher_no', $DeliveryChallan->voucher_no)
            //     ->where('type', 'DCNonGST')
            //     ->get();
                $deliverychallanDetail = DeliveryChallanDetails::with(['product' => function($query) use ($VoucherNo){
                    $query->with(['dc_details' => function($query) use ($VoucherNo){
                                // $query->where('sale_order_no', '=', $SaleOrderNo);
                                //  $query->where('voucher_no', '<=', $VoucherNo);
                                 $query->where('voucher_no', '=', $VoucherNo);
                                 $query->where('type', '=', "DCNonGST");
                             }]);
                 }])
                    ->with(['delivery_challan' => function ($query) {
                        $query->with('party', 'sale_order:id,voucher_no');
                    }])
                    ->where('voucher_no', $VoucherNo)
                    ->where('type', 'DCNonGST')
                    ->get();

            $pdf = PDF::loadView('delivery-challan-no-gst.invoice', compact('deliverychallanDetail'));
            $fileName =  'delivery-challan-no-gst' . $VoucherNo . '.pdf';
            $pdf->save(base_path('upload/delivery-challan-no-gst/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }
    public function dataFetch(Request $request)
    {
        // return "ddd";
        $status = '';
        $re = SaleOrderDetails::where('sale_order_id', $request->sale_order_id)->first();
        $products = CustomerProduct::with('product')->where('id', $re->product_id)->count();
        if ($products > 0) {
            // return "up";
            $status = 0;
            $saleorder = SaleOrderDetails::with(['customer_product' => function ($qury) {
                $qury->with('product:id,uom,product_name');
            }])
                ->with('party:id,party_name,address')
                ->with('sale_order:id,po_date,po_no')
                ->where('sale_order_id', $request->sale_order_id)
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
                ->with('sale_order:id,po_date,po_no')
                ->where('sale_order_id', $request->sale_order_id)
                ->where('type', 'SALE DEMAND')
                ->get();

            return Response::json(['data' => $saleorder, 'status' => $status]);
        }
    }
    public function Report(Request $request)
    {
        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $party_id = $request->party_id;
            $reportType = $request->report_type;
            if ($reportType == 'summary') {
                $summaryReport = DeliveryChallanDetails::join('products', 'products.id', '=', 'delivery_challan_details.product_id')
                    ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
                    ->select(
                        'parties.party_name',
                        DB::raw('SUM(delivery_challan_details.quantity) as quantity'),
                        DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty'),
                        'delivery_challan_details.voucher_date',
                        'delivery_challan_details.voucher_no',
                        'delivery_challan_details.demandqty',
                        'delivery_challan_details.packing')
                    ->where('delivery_challan_details.party_id', $party_id)
                    ->whereDate('delivery_challan_details.voucher_date', '>=', $fromDate)
                    ->whereDate('delivery_challan_details.voucher_date', '<=', $toDate)
                    ->groupBy('parties.party_name')
                    ->orderBy('parties.id')
                    ->get();
                return response()->json(['data' => $summaryReport]);
            }
            if ($reportType == 'detail') {
                $detailReport = DeliveryChallanDetails::with('party:id,party_name', 'product:id,product_name,code,uom')
                    ->where('party_id', $party_id)
                    ->whereDate('voucher_date', '>=', $fromDate)
                    ->whereDate('voucher_date', '<=', $toDate)
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

        return view('delivery-challan-no-gst.report', compact('customers'));
    }
}
