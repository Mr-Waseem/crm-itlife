<?php

namespace App\Http\Controllers;

use App\Models\GRN;
use App\Models\SalePurchase;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\GRNDetails;
use App\Models\GodownStock;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\InwardGatePass;
use App\Models\GodownStockDetail;
use Illuminate\Support\Facades\DB;
use App\Models\StockTransferDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\InwardGatePassDetails;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class GRNController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $codes = 1;
        $Godownstock = GodownStock::where('to_warehouse_id',Auth::User()->warehouse_id)
        ->where('type','GRN')->orderBy('id', 'desc')->first();
        if ($Godownstock) {
            $codes = $Godownstock->voucher_no + 1;
        }

        // return $codes;

        
        $inward_gatepasses = InwardGatePass::whereStatus(0)
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->pluck('bill_no', 'id')->prepend('Select IGP', '');
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`) AS `id`, `code`, `product_name`, `uom`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '')
            ->toArray();
        $warehouse=Warehouse::where('id',Auth::User()->warehouse_id)->pluck('name','id');
        return view('grn.index', compact('codes', 'inward_gatepasses', 'products','warehouse'));
    }

    public function store(Request $request)
    {
        // return $request;
        if (!isset($request->qty)) {
            return redirect()->back()->with('failure_message', 'Please Enter 1 Record');
        }
        if ($request->update_voucher_no != null || $request->update_voucher_no != 0) {
            $Validator = Validator::make($request->all(), [
                'date' => 'required',
                'voucher_no' => 'required',
                
            ]);
            if ($Validator->fails()) {
                return redirect()->back()->withErrors($Validator);
            }
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'GRN')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return Auth::User()->warehouse_id;
             $grn=GodownStock::where('voucher_no',$request->update_voucher_no)
             ->where('to_warehouse_id', Auth::User()->warehouse_id)
             ->where('type','GRN')->first();
             $inwardGatePass = InwardGatePass::find($request->inward);
           
            $godownstock=GodownStock::find($grn->id);
           $godownstock->update($request->all());
           $godownstock->updated_by=Auth::User()->id;
        //    $godownstock->to_warehouse_id=Auth::User()->warehouse_id;
           $godownstock->inward_gatepass_id=$request->inward;
           $godownstock->party_id=$inwardGatePass->supplier_id;;
           $godownstock->save();
           
            GodownStockDetail::where('transaction_id', $grn->id)->where('type','GRN')
            ->where('warehouse_id', Auth::User()->warehouse_id)->delete();
           RequestGenerateDetails::where('request_generate_id', $grn->id)->where('type','GRN')->delete();
            $count = count($request->product_id);
           for ($i = 0; $i < $count; $i++) {
              
               $godownstockDetails=new GodownStockDetail();
               $godownstockDetails->voucher_no = $godownstock->voucher_no;
               $godownstockDetails->transaction_id = $godownstock->id;
               $godownstockDetails->inward_gatepass_id = $godownstock->inward_gatepass_id;
               $godownstockDetails->date= $godownstock->date;
               $godownstockDetails->type = 'GRN';
               $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
               $godownstockDetails->party_id =$inwardGatePass->supplier_id;
               $godownstockDetails->product_id = $request->product_id[$i];
               $godownstockDetails->qty_in =$request->qty[$i];
               $godownstockDetails->qty_out =0;
               $godownstockDetails->demand_qty =$request->demandQty[$i];
               $godownstockDetails->remarks = $request->comments[$i];
               $godownstockDetails->created_by = Auth::User()->id;
               $godownstockDetails->save();
   
   
               $requestGenerateDetails = new RequestGenerateDetails();
               $requestGenerateDetails->request_generate_id = $godownstock->id;
               $requestGenerateDetails->date = $godownstock->date;
               $requestGenerateDetails->bill_no = $godownstock->voucher_no;
               $requestGenerateDetails->product_code = $request->code[$i];
               $requestGenerateDetails->supplier_id = $inwardGatePass->supplier_id;
               $requestGenerateDetails->purchaser_id = $inwardGatePass->purchaser_id;
               $requestGenerateDetails->product_id = $request->product_id[$i];
               $requestGenerateDetails->provided_qty = $request->qty[$i];
               $requestGenerateDetails->unit = $request->unit[$i];
               $requestGenerateDetails->comments = $request->comments[$i];
               $requestGenerateDetails->created_by = Auth::User()->id;
               $requestGenerateDetails->updated_by = Auth::User()->id;
               $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
               $requestGenerateDetails->status = 1;
               $requestGenerateDetails->type = "GRN";
               $requestGenerateDetails->save();
           }
           return redirect()->back()->with('flash_message', 'GRN Voucher Updated Successfully!');
       
        }else{
            $Validator = Validator::make($request->all(), [
                'date' => 'required',
                'voucher_no' => 'required',
                'inward_gatepass_id' => 'required'
            ], [
                'inward_gatepass_id.required' => 'The Inward gatepass field is required.'
            ]);
            if ($Validator->fails()) {
                return redirect()->back()->withErrors($Validator);
            }
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'GRN')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $inwardGatePass = InwardGatePass::find($request->inward_gatepass_id);
        $codes = 1;
        $Godownstock = GodownStock::where('to_warehouse_id',Auth::User()->warehouse_id)->where('type','GRN')->orderBy('id', 'desc')->first();
        if ($Godownstock) {
            $codes = $Godownstock->voucher_no + 1;
        }
        $data = $request->all();
        $data['voucher_no'] = $codes;
        $data['type'] ='GRN';
        $data['status'] =0;
        $data['to_warehouse_id'] =Auth::User()->warehouse_id;
        $data['created_by'] =Auth::User()->id;
        $data['party_id'] =$inwardGatePass->supplier_id;


        $godownstock = GodownStock::create($data);
        $count = count($request->product_id);
        for ($i = 0; $i < $count; $i++) {
           
            // $stockTransferDetails = new StockTransferDetails();
            // $stockTransferDetails->transfer_id = $grn->id;
            // $stockTransferDetails->date = $grn->voucher_date;
            // $stockTransferDetails->bill_no = $grn->voucher_no;
            // $stockTransferDetails->product_id = $request->product_id[$i];
            // $stockTransferDetails->warehouse_id = Auth::user()->warehouse_id;
            // $stockTransferDetails->qty_in = $request->qty[$i];
            // $stockTransferDetails->sale_rate = $request->price[$i];
            // $stockTransferDetails->sale_amount = $request->total[$i];
            // $stockTransferDetails->type = "GRN";
            // $stockTransferDetails->created_by = Auth::User()->id;
            // $stockTransferDetails->save();

            $godownstockDetails=new GodownStockDetail();
            $godownstockDetails->voucher_no = $godownstock->voucher_no;
            $godownstockDetails->transaction_id = $godownstock->id;
            $godownstockDetails->inward_gatepass_id = $godownstock->inward_gatepass_id;
            $godownstockDetails->date= $godownstock->date;
            $godownstockDetails->type = 'GRN';
            $godownstockDetails->warehouse_id = Auth::User()->warehouse_id;
            $godownstockDetails->party_id =$inwardGatePass->supplier_id;
            $godownstockDetails->product_id = $request->product_id[$i];
            $godownstockDetails->qty_in =$request->qty[$i];
            $godownstockDetails->qty_out =0;
            $godownstockDetails->demand_qty =0;
             $godownstockDetails->demand_qty =$request->demandQty[$i];
            $godownstockDetails->remarks = $request->comments[$i];
            $godownstockDetails->created_by = Auth::User()->id;
            $godownstockDetails->save();




            $requestGenerateDetails = new RequestGenerateDetails();
            $requestGenerateDetails->request_generate_id = $godownstock->id;
            $requestGenerateDetails->date = $godownstock->date;
            $requestGenerateDetails->bill_no = $godownstock->voucher_no;
            $requestGenerateDetails->product_code = $request->code[$i];
            $requestGenerateDetails->supplier_id = $inwardGatePass->supplier_id;
            $requestGenerateDetails->purchaser_id = $inwardGatePass->purchaser_id;
            $requestGenerateDetails->product_id = $request->product_id[$i];
            $requestGenerateDetails->provided_qty = $request->qty[$i];
            $requestGenerateDetails->unit = $request->unit[$i];
            $requestGenerateDetails->comments = $request->comments[$i];
            $requestGenerateDetails->created_by = Auth::User()->id;
            $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
            $requestGenerateDetails->status = 1;
            $requestGenerateDetails->type = "GRN";
            $requestGenerateDetails->save();
        }
        $inwardGatePass->update(['status' => 1]);
        InwardGatePassDetails::where('inward_gatepass_id', $request->inward_gatepass_id)->update([
            'status' => 1
        ]);

        return redirect()->back()->with('flash_message', 'GRN Voucher Added Successfully!');
    } 
 }

    public function LoadIGPData(Request $request)
    {
        $inward = InwardGatePass::whereId($request->inward_gatepass_id)->first();
        if ($inward) {
            $inwardGatePassDetails = InwardGatePassDetails::with(['inward'=>function($qry){
                $qry->with('supplier:id,party_name','purchaser:id,party_name');
            }])
            ->with('product')
            ->where('inward_gatepass_id', $request->inward_gatepass_id)->get();
            return Response::json(['data' => $inwardGatePassDetails]);
        } else {
            return Response::json(['data' => '']);
        }
        abort(500);
    }

    public function editData(Request $request)
    {
        $godownstock = GodownStock::where('voucher_no', $request->voucher_no)->where('type','GRN')->where('to_warehouse_id',Auth::User()->warehouse_id)->first();
        if ($godownstock) {
            $godownstockDetails = GodownStockDetail::with(['godownstock'=>function($qry){
                $qry->with(['inward_gatepass'=>function($query){
                    $query->with('supplier:id,party_name','purchaser:id,party_name');
                }]);
                $qry ->with('warehouse:id,name');
            }])
               ->with('product:id,product_name,code,uom')
                ->where('voucher_no', $godownstock->voucher_no)
                ->where('type','GRN')
                ->where('warehouse_id',Auth::User()->warehouse_id)
                ->get();
            return Response::json(['data' => $godownstockDetails]);
        } else {
            return Response::json(['data' => '']);
        }
       
    }

    public function LoadNextData(Request $request)
    {
        $godownstock = GodownStock::where('voucher_no', '>', $request->voucher_no)->where('type','GRN')->where('to_warehouse_id',Auth::User()->warehouse_id)->min('voucher_no');

        if ($godownstock) {
            $godownstockDetails = GodownStockDetail::with(['godownstock'=>function($qry){
                $qry->with(['inward_gatepass'=>function($query){
                    $query->with('supplier:id,party_name','purchaser:id,party_name');
                }]);
                $qry ->with('warehouse:id,name');
            }])
               ->with('product:id,product_name,code,uom')
                ->where('voucher_no', $godownstock)
                ->where('type','GRN')
                ->where('warehouse_id',Auth::User()->warehouse_id)
                ->get();
            return Response::json(['data' => $godownstockDetails]);
        } else {
            return Response::json(['data' => '']);
        }
      
    }

    public function LoadPreviousData(Request $request)
    {
        $godownstock = GodownStock::where('voucher_no', '<', $request->voucher_no)->where('type','GRN')->where('to_warehouse_id',Auth::User()->warehouse_id)->max('voucher_no');
        if ($godownstock) {
            $godownstockDetails = GodownStockDetail::with(['godownstock'=>function($qry){
                $qry->with(['inward_gatepass'=>function($query){
                    $query->with('supplier:id,party_name','purchaser:id,party_name');
                }]);
                $qry ->with('warehouse:id,name');
            }])
               ->with('product:id,product_name,code,uom')
                ->where('voucher_no', $godownstock)
                ->where('type','GRN')
                ->where('warehouse_id',Auth::User()->warehouse_id)
                ->get();
            return Response::json(['data' => $godownstockDetails]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function destroy(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'GRN')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $GodownStock = GodownStock::where('voucher_no', $request->delete_voucher_no)->where('type','GRN')
        ->where('to_warehouse_id',Auth::User()->warehouse_id)->first();

         $purchase = SalePurchase::where('grn_dc_id', $GodownStock->id)
        ->where('warehouse_id',Auth::User()->warehouse_id)
        ->where('type', 'PURCHASE')
        ->first();
        // if ($GodownStock) {
        //     InwardGatePass::find($GodownStock->inward_gatepass_id)->update(['status' => 0]);
        //     InwardGatePassDetails::where('inward_gatepass_id', $GodownStock->inward_gatepass_id)->update(['status' => 0]);
        //     GodownStock::where('voucher_no', $request->delete_voucher_no)->where('type','GRN')->where('to_warehouse_id',Auth::User()->warehouse_id)->delete();
        //     GodownStockDetail::where('voucher_no', $request->delete_voucher_no)->where('type','GRN')->where('warehouse_id',Auth::User()->warehouse_id)->delete();
        //     // StockTransferDetails::where('transfer_id', $grn->id)->whereType('GRN')->delete();
        //     RequestGenerateDetails::where('request_generate_id', $GodownStock->id)->whereType('GRN')->delete();
        //     return redirect()->back()->with('flash_message', 'GRN Voucher Deleted Successfully!');
        // } else {
        //     return redirect()->back()->with('error_message', 'Voucher No Not Exist!');
        // }

        if ($purchase) {
            return redirect()->back()->with('access_granted', 'Delete Purchase of this GRN First!');
        } else {
            if ($GodownStock) {
                InwardGatePass::where('id', $GodownStock->inward_gatepass_id)->update(['status' => 0]);
                InwardGatePassDetails::where('inward_gatepass_id', $GodownStock->inward_gatepass_id)->update(['status' => 0]);
                GodownStock::where('voucher_no', $request->delete_voucher_no)->where('type','GRN')->where('to_warehouse_id',Auth::User()->warehouse_id)->delete();
                GodownStockDetail::where('voucher_no', $request->delete_voucher_no)->where('type','GRN')->where('warehouse_id',Auth::User()->warehouse_id)->delete();
                // StockTransferDetails::where('transfer_id', $grn->id)->whereType('GRN')->delete();
                RequestGenerateDetails::where('request_generate_id', $GodownStock->id)->whereType('GRN')->delete();
                return redirect()->back()->with('flash_message', 'GRN Voucher Deleted Successfully!');
            }else{
                return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
            }
        }

        abort(500);
    }
     public function PrintVoucher(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'GRN')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }

        // File::cleanDirectory(base_path() .'/upload/grn');
        $voucher_no = $request->voucher_no;
        $godownStock = GodownStock::where('voucher_no', $voucher_no)->where('type','GRN')->where('to_warehouse_id',Auth::User()->warehouse_id)->first();
        if ($godownStock) {
             $godownStockDetail = GodownStockDetail::with(['godownstock'=>function($qry){
                $qry->with(['inward_gatepass'=>function($query){
                    $query->with('supplier:id,party_name','purchaser:id,party_name');
                    $query->with('request_generate:id,bill_no');
                }]);
                $qry->with('user:id,name','warehouse:id,name');
            }])
                 ->with('product:id,product_name,uom,code')
                 ->where('transaction_id',$godownStock->id)
                 ->where('type','GRN')
                 ->where('warehouse_id',Auth::User()->warehouse_id)
                ->orderBy('id', 'asc')
                ->get();

            $pdf = PDF::loadView('grn.invoice', compact('godownStockDetail'));
            $fileName =  'grn'. $voucher_no . '.pdf';
            $pdf->save(base_path('upload/grn/' . $fileName));
            return $fileName;
        } else{
            return false;
        }
    }




}