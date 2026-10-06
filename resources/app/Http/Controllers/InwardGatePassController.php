<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\GodownStock;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\InwardGatePass;
use App\Models\RequestGenerate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\InwardGatePassDetails;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables as DataTables;
class InwardGatePassController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $codes = 1;
        $inwardGatePass = InwardGatePass::orderBy('id', 'desc')->first();
        if ($inwardGatePass) {
            $codes = $inwardGatePass->bill_no + 1;
        }

        $suppliers = Party::where('account_type', 'SUPPLIER')->pluck('party_name', 'id')->prepend("No Supplier", 0);
        $requestgenerate=RequestGenerate::where('status',0)->
        select(DB::raw('`id`, CONCAT(`bill_no`,"-",`warehouse_name`) as `bill_no`'))->pluck('bill_no','id')->prepend("Select Request", "");
        $purchasers = Party::where('account_type', 'PURCHASER')->pluck('party_name', 'id')->prepend("Select Purchaser", "");
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`) AS `id`, `code`, `product_name`, `uom`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '')
            ->toArray();
            $warehouse=Warehouse::pluck('name','id')->prepend('Select Warehouse', '');
        return view('inward-gatepass.index', compact('codes', 'purchasers', 'suppliers', 'products','requestgenerate','warehouse'));
    }
    public function store(Request $request)
    {
    //  return $request;
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'bill_no' => 'required',
            'vehicle_no' => 'required',
            'transport_company' => 'required',
            'driver_name' => 'required',
            'builty_no' => 'required',
            'supplier_id' => 'required',
            'driver_phoneno' => 'required',
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }
        if (!isset($request->qty)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }

        if ($request->update_bill_no != null || $request->update_bill_no != 0) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'INWARD GATEPASS')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $inward = InwardGatePass::where('bill_no', $request->update_bill_no)->first();

            $inwardGatePass = InwardGatePass::find($inward->id);
            $data = $request->all();
            $data['req_gen_id'] = $request->req_gen_id_edit;
            $inwardGatePass->update($data);
            $inwardGatePass->save();

            $count = count($request->product_id);
            $totalQty = 0; $totalsum=0;
            
            InwardGatePassDetails::where('inward_gatepass_id', $inward->id)->delete();
            // return "s";
            for ($i = 0; $i < $count; $i++) {
                $requestGenerateDetails = new InwardGatePassDetails();
                $requestGenerateDetails->inward_gatepass_id = $inwardGatePass->id;
                $requestGenerateDetails->bill_no = $inwardGatePass->bill_no;
                $requestGenerateDetails->request_no = $request->request_no[$i];
                $requestGenerateDetails->date = $inwardGatePass->date;
                $requestGenerateDetails->supplier_id = $inwardGatePass->supplier_id;
                $requestGenerateDetails->purchaser_id = $inwardGatePass->purchaser_id;
                $requestGenerateDetails->warehouse_id = $inwardGatePass->warehouse_id;
                $requestGenerateDetails->request_detail_id = $request->request_detail_id[$i];
                $requestGenerateDetails->product_code = $request->code[$i];
                $requestGenerateDetails->product_id = $request->product_id[$i];
                $requestGenerateDetails->product_name = $request->product_name[$i];
                $requestGenerateDetails->unit = $request->unit[$i];
                $requestGenerateDetails->qty = $request->qty[$i];
                $requestGenerateDetails->qtyshow = $request->qtyforshow[$i];
                $requestGenerateDetails->comments = $request->comments[$i];
                $requestGenerateDetails->created_by = Auth::User()->id;
                $requestGenerateDetails->updated_by = Auth::User()->id;
                $requestGenerateDetails->status = $inwardGatePass->status;
                $requestGenerateDetails->save();

                $totalQty += $request->qty[$i];
                // return $request;
                if(($request->balance[$i]) <= 0){
                    $rqG=RequestGenerateDetails::where('id', $request->request_detail_id[$i])->first();
                    if($rqG){
                    $rqG->status=1;
                    $rqG->save();
                    }
                }else{
                    // return "Dd";
                     $rqG=RequestGenerateDetails::where('id', $request->request_detail_id[$i])->first();
                    if($rqG){
                    $rqG->status=0;
                    $rqG->save();
                    }
                }
            }
            $inwardGatePass->total_qty = $totalQty;
            $inwardGatePass->updated_by = Auth::User()->id;
            $inwardGatePass->save();

            for ($i = 0; $i < $count; $i++) 
            {   
                $requestdata = RequestGenerate::with('request_generate_details')
                ->where('request_generates.bill_no', $request->request_no[$i])->first();
                foreach($requestdata->request_generate_details as $data){
                    $rqG = RequestGenerate::where('bill_no', $request->request_no[$i])->first();
                    if($data->status == "0"){
                        // $totalsum += 1;
                        if($rqG){
                        $rqG->status=0;
                        $rqG->save();
                        }
                    }else{
                        if($rqG){
                            $rqG->status=1;
                            $rqG->save();
                            }
                    }
                }
            
            }

            return redirect()->back()->with('flash_message', 'Inward GatePass Updated Successfully!');
        } else {
         
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'INWARD GATEPASS')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            $codes = 1;
            $inwardGatePass = InwardGatePass::orderBy('id', 'desc')->first();
            if ($inwardGatePass) {
                $codes = $inwardGatePass->bill_no + 1;
            }
            $data = $request->all();
            $data['bill_no'] = $codes;
            // $data['warehouse_id'] = Auth::User()->warehouse_id;
            $inwardGatePass = InwardGatePass::create($data);
            $count = count($request->request_detail_id);
            $totalQty = 0; $totalsum=0;
            // return $request->balance;
            for ($i = 0; $i < $count; $i++) 
            {
                if($request->qty[$i] > 0){
                    $requestGenerateDetails = new InwardGatePassDetails();
                    $requestGenerateDetails->inward_gatepass_id = $inwardGatePass->id;
                    $requestGenerateDetails->bill_no = $inwardGatePass->bill_no;
                    $requestGenerateDetails->request_no = $request->request_no[$i];
                    $requestGenerateDetails->date = $inwardGatePass->date;
                    $requestGenerateDetails->supplier_id = $inwardGatePass->supplier_id;
                    $requestGenerateDetails->purchaser_id = $inwardGatePass->purchaser_id;
                    $requestGenerateDetails->warehouse_id = $inwardGatePass->warehouse_id;
                    $requestGenerateDetails->request_detail_id = $request->request_detail_id[$i];
                    $requestGenerateDetails->product_code = $request->code[$i];
                    $requestGenerateDetails->product_id = $request->product_id[$i];
                    $requestGenerateDetails->product_name = $request->product_name[$i];
                    $requestGenerateDetails->unit = $request->unit[$i];
                    $requestGenerateDetails->qty = $request->qty[$i];
                    $requestGenerateDetails->qtyshow = $request->qtyforshow[$i];
                    $requestGenerateDetails->comments = $request->comments[$i];
                    $requestGenerateDetails->created_by = Auth::User()->id;
                    $requestGenerateDetails->status = $inwardGatePass->status;
                    $requestGenerateDetails->save();
                    $totalQty += $request->qty[$i];
                    // return $request->balance;
                    if(($request->balance[$i]) <= 0){
                        $rqG=RequestGenerateDetails::where('id', $request->request_detail_id[$i])->first();
                        $rqG->status=1;
                        $rqG->save();
                    }
                }
               
            }
            $inwardGatePass->total_qty = $totalQty;
            $inwardGatePass->save();
            // return $request;
                // Request Generate status;
            for ($i = 0; $i < $count; $i++) 
            {
                if($request->qty[$i] > 0){
                // return $count;
                 $requestdata = RequestGenerate::with('request_generate_details')
                ->where('request_generates.bill_no', $request->request_no[$i])->first();
                foreach($requestdata->request_generate_details as $data){
                    $rqG = RequestGenerate::where('bill_no', $request->request_no[$i])->first();
                    if($data->status == "0"){
                        // $totalsum += 1;
                        if($rqG){
                        $rqG->status=0;
                        $rqG->save();
                        }

                    }else{
                        if($rqG){
                            $rqG->status=1;
                            $rqG->save();
                            }
                    }
                }
            }
            }
           
            return redirect()->back()->with('flash_message', 'Inward GatePass Added Successfully!');
        }
        abort(500);
    }

    public function editData(Request $request)
    {
         $inward = InwardGatePass::where('bill_no', $request->bill_no)->first();
        $VoucherNo = $inward->bill_no;
         $SaleOrder = InwardGatePass::where('bill_no', '=', $VoucherNo)->get();
        //79
        $SaleOrderNo = $SaleOrder[0]->bill_no;
        if ($inward) {
             
              $inwardGatePassDetails = InwardGatePassDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
                $query->with(['gatepass_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                           // $query->where('request_order_no', '=', $SaleOrderNo);
                            $query->where('bill_no', '<=', $VoucherNo);
                        }]);
            }])
            
            ->with(['inward'=>function($query){
                $query->with('request_generate:id,bill_no,warehouse_name,date');
                $query->with('supplier:id,party_name');
                $query->with('purchaser:id,party_name');
            }])
            // ->with('gatepass_details') 
            // ->with('product:id,product_name,code,uom')
            ->where('bill_no', $request->bill_no)->get();
            return Response::json(['data' => $inwardGatePassDetails]);
        } else {
            return Response::json(['data' => '']);
        }
        abort(500);
    }


    public function WarehouseRequests(Request $request){
        // return $request;
        $requests=RequestGenerate::where('status',0)
        ->select(DB::raw('`id`, CONCAT(`bill_no`,"-",`warehouse_name`) as `bill_no`'))
        ->where('warehouse_id', $request->warehouseID)
        ->get();
        if ($requests) {
            return Response::json(['data' => $requests]);
        } else {
            return Response::json(['data' => '']);
        }

        // $requestgenerate=RequestGenerate::where('status',0)
        // ->select(DB::raw('`id`, CONCAT(`bill_no`,"-",`warehouse_name`) as `bill_no`'))
        // ->pluck('bill_no','id')->prepend("Select Request", "");

        


    }

    public function LoadNextData(Request $request)
    {
         $inward = InwardGatePass::where('bill_no', '>', $request->bill_no)->min('bill_no');
        $VoucherNo = $inward;
         $SaleOrder = InwardGatePass::where('bill_no', '=', $VoucherNo)->get();
       //79
       $SaleOrderNo = $SaleOrder[0]->bill_no;
        if ($inward) {
            $inwardGatePassDetails = InwardGatePassDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
                $query->with(['gatepass_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                           // $query->where('request_order_no', '=', $SaleOrderNo);
                            $query->where('bill_no', '<=', $VoucherNo);
                        }]);
            }])
            
            ->with(['inward'=>function($qry){
                $qry->with('request_generate:id,bill_no,warehouse_name,date');
                $qry->with('supplier:id,party_name');
                $qry->with('purchaser:id,party_name');
            }])
            // ->with('gatepass_details') 
            // ->with('product:id,product_name,code,uom')
            ->where('bill_no', $inward)->get();
            return Response::json(['data' => $inwardGatePassDetails]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        // return $request;
        $inward = InwardGatePass::where('bill_no', '<', $request->bill_no)->max('bill_no');
         $VoucherNo = $inward;
         $SaleOrder = InwardGatePass::where('bill_no', '=', $VoucherNo)->get();
          $SaleOrderNo = $SaleOrder[0]->bill_no;
        //  return $SaleOrderNo = $SaleOrder[0]->request_order_no;
        if ($inward) {
             $inwardGatePassDetails = InwardGatePassDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
                 $query->with(['gatepass_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                            // $query->where('request_order_no', '=', $SaleOrderNo);
                             $query->where('bill_no', '<=', $VoucherNo);
                         }]);
             }])
            
            ->with(['inward'=>function($query){
                $query->with('request_generate:id,bill_no,warehouse_name,date');
                $query->with('supplier:id,party_name');
                $query->with('purchaser:id,party_name');
            }])
            // ->with('gatepass_details') 
            // ->with('product:id,product_name,code,uom')
            ->where('bill_no', $inward)->get();

            // $record = RequestGenerateDetails::with(['request_generate'=>function($qry){
            //     $qry->with('supplier:id,party_name','purchaser:id,party_name');
            // }])
            //     ->with('product')
            //     ->where('request_generate_id', $request->req_gen_id)
            //     ->orderBy('id', 'asc')
            //     ->get();


            return Response::json(['data' => $inwardGatePassDetails]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function destroy(Request $request)
    {
        //  return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'INWARD GATEPASS')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

      


            $inward = InwardGatePass::where('bill_no', $request->delete_bill_no)->first();
            $grn = GodownStock::where('inward_gatepass_id', $inward->id)
        ->where('type', 'GRN')->first();

        if ($grn) {
            return redirect()->back()->with('access_granted', 'Delete GRN of this IGP First!');
        } else {
            if ($inward) {
                // return "yes";
                //  $uptdstatus=RequestGenerate::find($inward->req_gen_id);
                // if($uptdstatus){
                //     $uptdstatus->status=0;
                //     $uptdstatus->save();
                //     }
                    //  $requestdetails = InwardGatePassDetails::with('request_detail')
                    // ->where('inward_gatepass_id', $inward->id)->get();
                      $requestdetails = InwardGatePassDetails::where('bill_no', $request->delete_bill_no)
                    // ->where('inward_gatepass_id', $inward->id)
                    ->get();
                     foreach($requestdetails as $data){
                        // return $data;
                         $requestgen = RequestGenerate::where('bill_no', $data->request_no)->first();
                        $requestgen->status = 0;
                        $requestgen->save();
                        //   return $data;
                         $requestDetail = RequestGenerateDetails::where('id', $data->request_detail_id)->first();
                        $requestDetail->status = 0;
                        $requestDetail->save();
                    }
                    // return $request->delete_bill_no;
                InwardGatePass::where('bill_no', $request->delete_bill_no)->delete();
                InwardGatePassDetails::where('bill_no', $request->delete_bill_no)->delete();
                return redirect()->back()->with('flash_message', 'Inward GatePass Deleted Successfully!');
            }else{
                return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
            }
        }
        // if ($inward) {
        //     // InwardGatePass::where('bill_no', $request->delete_bill_no)->delete();
        //     // InwardGatePassDetails::where('bill_no', $request->delete_bill_no)->delete();
        //     // $uptdstatus=RequestGenerate::find($inward->req_gen_id);
        //     // if($uptdstatus){
        //     // $uptdstatus->status=0;
        //     // $uptdstatus->save();
        //     // }
        //     // return redirect()->back()->with('flash_message', 'Inward GatePass Deleted Successfully!');
        // } else {
        //     return redirect()->back()->with('error_message', 'Voucher No Not Exist!');
        // }
        abort(500);
    }
    public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'INWARD GATEPASS')
        ->where('right_name', 'PRINT')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        File::cleanDirectory(base_path() .'/upload/inward-gatepass');
        
        $bill_no = $request->bill_no;
        $VoucherNo = $request->bill_no;
        //  $SaleOrder = InwardGatePass::where('bill_no', '=', $VoucherNo)->get();
       //79
       
        $inwardgatePass = InwardGatePass::where('bill_no', $bill_no)->first();
        $SaleOrderNo = $inwardgatePass->bill_no;
        if ($inwardgatePass) {
            $inwardgatepassDetails = InwardGatePassDetails::with(['product' => function($query) use ($SaleOrderNo, $VoucherNo){
                $query->with(['gatepass_details' => function($query) use ($SaleOrderNo, $VoucherNo){
                           // $query->where('request_order_no', '=', $SaleOrderNo);
                            $query->where('bill_no', '<=', $VoucherNo);
                        }]);
            }])
            
            ->with(['inward'=>function($qry){
                            $qry->with('supplier:id,party_name','purchaser:id,party_name','warehouse:id,name');
            }])
            // ->with('gatepass_details') 
                // ->with('product:id,product_name,uom,code')
                ->where('bill_no', $bill_no)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('inward-gatepass.invoice', compact('inwardgatepassDetails'));
            $fileName =  'inward-gatepass' . $bill_no . '.pdf';
            $pdf->save(base_path('upload/inward-gatepass/'. $fileName));
            return $fileName;
        } else{
            return false;
        }
    }

    public function requestgenerate(Request $request)
    {
        //   return $request;
        $ReqGenID = $request->ReqID;
        // return $ReqGenID = $ReqGenID1;
         $record = RequestGenerateDetails::with(['request_generate'=>function($qry){
            $qry->with('supplier:id,party_name','purchaser:id,party_name');
            
        }])
            ->with('gatepass_details') 
            ->with('product')
            // ->wherein('request_generate_id', 4,4)
            // ->wherein('id', [6,7])
            ->whereIn('id', $request->ReqID)
             ->where('status', '0')
            // ->where('type', 'Request Generate')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->orderBy('id', 'asc')
            ->get();
        if ($record) {
            return Response::json(['data' => $record]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadRequestData(Request $request)
    {
        // return $request;
        //  return $request->partyid;
        if ($request->ajax()) {

             $RequestID = $request->ReqID;

                //  $stock1 = SaleOrderDetails::
                // join('products', 'products.id', '=', 'sale_order_details.product_id')
                // ->join('sale_orders', 'sale_orders.id', '=', 'sale_order_details.sale_order_id')
                // ->with('dc_details2') 
                // ->select(
                //         'sale_order_details.id',
                //         'sale_order_details.voucher_no',
                //         'sale_order_details.voucher_date',
                //         'sale_orders.po_no',
                //         'sale_orders.po_date',
                //         'products.product_name',
                //         'sale_order_details.order_qty',
                //         )
                //         //->
                //         ->where('sale_order_details.type', 'SALE DEMAND')
                //         ->where('sale_order_details.status', 0)
                //         ->where('sale_order_details.party_id', $request->partyid)
                //         // ->orderBy('sale_order_details.id')
                //         ->get();

                        $stock1 = RequestGenerateDetails::with(['request_generate'=>function($qry){
                            $qry->with('supplier:id,party_name','purchaser:id,party_name');
                            
                        }])
                            ->with('gatepass_details') 
                            ->with('product')
                            ->where('request_generate_id', $RequestID)
                             ->where('status', '0')
                            // ->where('type', 'Request Generate')
                            // ->where('warehouse_id', Auth::User()->warehouse_id)
                            ->orderBy('id', 'asc')
                            ->get();
                        
                        $listarray = array();
                        $newData = array();
                        $voucherNo = 0;
                        
                         foreach($stock1 as $data){
                            $totalqty = 0;
                            foreach($data->gatepass_details as $value){
                                $totalqty = $totalqty + $value->qty;
                                 
                            }
                                // $newData['id']=$data->id;
                                // $newData['voucher_no']=$data->voucher_no;
                                // $newData['voucher_date']=$data->voucher_date;
                                // $newData['product_name']=$data->product_name;
                                // $newData['po_no']=$data->po_no;
                                // $newData['po_date']=$data->po_date;
                                // $newData['order_qty']=$data->order_qty;
                                // $newData['sale_qty']=$totalqty;

                                $newData['id']=$data->id;
                                $newData['request_no']=$data->bill_no;
                                $newData['code']=$data->product->code;
                                $newData['product_name']=$data->product->product_name;
                                
                                $newData['unit']=$data->product->uom;
                                $newData['demandqty']=$data->qty;
                                $newData['receive_qty']=$totalqty;
                                $newData['comment']=$data->comments;
                                // $newData['unit']=$data->product->uom;
                                // $newData['voucher_date']=$data->voucher_date;
                                // $newData['product_name']=$data->product_name;
                                // $newData['po_no']=$data->po_no;
                                // $newData['po_date']=$data->po_date;
                                // $newData['order_qty']=$data->order_qty;
                                // $newData['sale_qty']=$totalqty;
                             
                             $listarray[] = $newData;
                         }

            return DataTables::of($listarray)
           
                ->addIndexColumn()
                ->editColumn('selectValue', function ($row) {
                    $idvalue = $row['id'];
                    $btn = "<input type='checkbox' name='checkbox_consignment[]' class='checkbox_type' value='$idvalue' id='$idvalue'/><label for='$idvalue'></label>";
                    return $btn;
                })
                ->editColumn('product_name', function ($row) {
                    // $btn = "<a href='javascript:void(0)' class='select-grnno text-primary' id=" . $row->voucher_no . '_' . $row->voucher_no . "><u>" . $row->voucher_no . "</u></a>";
                    return $row['product_name'];
                    // return $btn;
                })
                // ->editColumn('date', function ($row) {
                //     return date('d/m/Y', strtotime($row['voucher_date']));
                //     // return json_encode($row);
                // })
                ->editColumn('request_no', function ($row) {
                    return $row['request_no'];
                })
                ->editColumn('code', function ($row) {
                    return $row['code'];
                })
                ->editColumn('unit', function ($row) {
                    return $row['unit'];
                })
                ->editColumn('demandqty', function ($row) {
                    return $row['demandqty'];
                })
                ->editColumn('balance', function ($row) {
                    return $row['demandqty'] - $row['receive_qty'];
                })
                ->editColumn('comment', function ($row) {
                    return $row['comment'];
                })
                // ->editColumn('po_no', function ($row) {
                //     return $row['po_no'];
                // })
                // ->editColumn('po_date', function ($row) {
                //     return date('d/m/Y', strtotime($row['po_date']));
                // })
                // ->editColumn('order_qty', function ($row) {
                //     return $row['order_qty'];
                // })
                // ->editColumn('balance', function ($row) {
                //     return $row['order_qty'] - $row['sale_qty'];
                // })
                ->rawColumns(['selectValue'])
                ->make(true);
        }
    }

    public function LoadEditRequestData(Request $request)
    {
        //  return $request;
        //  return $request->partyid;
        if ($request->ajax()) {
             $RequestID = $request->ReqID;
              $stock1 = RequestGenerate::with('supplier:id,party_name','purchaser:id,party_name')
            ->with(['request_generate_details' => function($query){
                $query->with('product:id,code,product_name,uom');
            }])
            ->where('id', $RequestID)->get();
            return Response::json(['data' => $stock1]);
        }
    }
}