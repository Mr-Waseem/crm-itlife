<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\RequestGenerate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;


class PurchaseOrderController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        // return "ddd";
        $requestGenerate = RequestGenerate::where('warehouse_id', Auth::User()->warehouse_id)
        ->whereType('PO')
        // ->orderBy('id', 'desc')
        ->max('po');
        // ->first();
        $codes = 1;
        
        if ($requestGenerate) {
            $codes = $requestGenerate + 1;
        }
        // return $codes;
        // $suppliers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`, `address`'))
        //     ->where('account_type', 'SUPPLIER')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party Name', '');


        $suppliers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->where('account_type', 'SUPPLIER')
        ->OrderBy('code', 'asc')
        ->pluck('party_name', 'id')
        ->prepend('Select Party Name', '');

        // $purchasers = Party::whereRole('Purchaser')
        // ->pluck('party_name', 'id')
        // ->prepend('Select Purchaser', '');

        $purchasers = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->whereRole('Purchaser')
        ->OrderBy('code', 'asc')
        ->pluck('party_name', 'id')
        ->prepend('Select Purchaser', '');


        $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`,"_",`tax`) AS `id`, CONCAT(`code`," ",`product_name`) `product_name`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('product_type', 'Normal')
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')

            ->prepend('Select Product', '');
        $warehouse = Warehouse::find(Auth::User()->warehouse_id);

        return view('purchase-order.index', compact('codes', 'purchasers', 'suppliers', 'products', 'warehouse'));
    }

    public function store(Request $request)
    {
        // return $request->all();
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'voucher_no' => 'required',
            'supplier_id' => 'required'
        ], [
            'voucher_no.required' => 'The Voucher number field is required.',
            'supplier_id.required' => 'The supplier field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        if (!isset($request->qty)) {
            return redirect()->back()->with('failure_message', 'Please Enter at least 1 Product');
        }
        if (!isset($request->rate)) {
            return redirect()->back()->with('failure_message', 'Please Enter at least 1 Product');
        }


        
        if ($request->update_voucher_id != null) {
              return $request->all();
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE ORDER')
            ->where('right_name', 'EDIT')
            ->first();
            if(!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return $changeStatus = RequestGenerate::where('po', $request->bill_no)->first();
            // $changeStatus->type = "";
            // $changeStatus->type = "";
             return $requestGenerateFirstRecord = RequestGenerate::where('id', $request->update_voucher_id)->first();
            // $requestGenerateFirstRecord = RequestGenerate::whereType('Purchase Order')->where('id', $request->update_voucher_id)->first();
            $requestGenerate = RequestGenerate::find($requestGenerateFirstRecord->id);
            // return $codes;
            // $requestGenerate = RequestGenerate::where('warehouse_id', Auth::User()->warehouse_id)
            // ->whereType('PO')
            // ->orderBy('id', 'desc')->first();
            // $codes = 1;
            // // return $codes;
            // if ($requestGenerate) {
            //     $codes = $requestGenerate->po + 1;
            // }
            $data = $request->all();
            $data['supplier_id'] = explode('_', $request->supplier_id)[0];
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $data['po_updated_by'] = Auth::User()->id;
            $data['type'] = "PO";
            $data['date'] = $requestGenerateFirstRecord->date;
            $data['po_date'] = $request->date;
            // $data['po'] = $codes;
            $requestGenerate->update($data);
            return $requestGenerate;
            RequestGenerateDetails::where('request_generate_id', $requestGenerateFirstRecord->id)->delete();
            // RequestGenerateDetails::whereType('Purchase Order')->where('request_generate_id', $requestGenerateFirstRecord->id)->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                $requestGenerateDetails = new RequestGenerateDetails();
                $requestGenerateDetails->request_generate_id = $requestGenerate->id;
                $requestGenerateDetails->bill_no = $requestGenerate->bill_no;
                $requestGenerateDetails->tax_with_holding = $requestGenerate->tax_with_holding;
                $requestGenerateDetails->date = $requestGenerate->date;
                $requestGenerateDetails->product_code = $request->code[$i];
                $requestGenerateDetails->purchaser_id = $requestGenerate->purchaser_id;
                $requestGenerateDetails->supplier_id = $requestGenerate->supplier_id;
                $requestGenerateDetails->product_id = $request->product_id[$i];
                $requestGenerateDetails->qty = $request->qty[$i];
                $requestGenerateDetails->rate = $request->rate[$i];
                $requestGenerateDetails->excl_val = $request->excl_val[$i];
                $requestGenerateDetails->st_rate = $request->st_rate[$i];
                $requestGenerateDetails->sale_tax = $request->sale_tax[$i];
                $requestGenerateDetails->total = $request->total[$i];
                $requestGenerateDetails->comments = $request->comments[$i];
                $requestGenerateDetails->unit = 1;
                $requestGenerateDetails->created_by = $requestGenerate->created_by;
                $requestGenerateDetails->updated_by = Auth::User()->id;
                $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
                $requestGenerateDetails->status = $requestGenerate->status;
                $requestGenerateDetails->type = $requestGenerate->type;
                $requestGenerateDetails->po = $requestGenerate->po;
                $requestGenerateDetails->po_date = $requestGenerate->po_date;
                $requestGenerateDetails->save();
            }

            return redirect()->back()->with('flash_message', 'Purchase Order Vouchers Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASE ORDER')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
       
       
        // $requestGenerateFirstRecord = RequestGenerate::where('id', $request->update_voucher_id)->first();
        // $requestGenerateFirstRecord = RequestGenerate::whereType('Purchase Order')->where('id', $request->update_voucher_id)->first();
        // $requestGenerate = RequestGenerate::find($requestGenerateFirstRecord->id);
        // return $requestGenerate;
        $requestCode = RequestGenerate::where('warehouse_id', Auth::User()->warehouse_id)
        ->where('po', '!=', 0)
        ->orderBy('id', 'desc')->first();
        $codes = 1;
        //  return $requestCode;
        if ($requestCode) {
            $codes = $requestCode->po + 1;
        }
        // return $codes;
        $requestGenerate = RequestGenerate::where('bill_no', $request->request_no)->first();
        $data = $request->all();
        $data['supplier_id'] = explode('_', $request->supplier_id)[0];
        $data['warehouse_id'] = Auth::User()->warehouse_id;
        $data['po_created_by'] = Auth::User()->id;
        $data['type'] = "PO";
        $data['po'] = $codes;
        $data['date'] = $requestGenerate->date;
        $data['po_date'] = $request->date;
        // return $data;
        $requestGenerate->update($data);
return $requestGenerate;
        RequestGenerateDetails::where('request_generate_id', $requestGenerate->id)->delete();
        // RequestGenerateDetails::whereType('Purchase Order')->where('request_generate_id', $requestGenerateFirstRecord->id)->delete();
        $count = count($request->product_id);
        for ($i = 0; $i < $count; $i++) {
            $requestGenerateDetails = new RequestGenerateDetails();
            $requestGenerateDetails->request_generate_id = $requestGenerate->id;
            $requestGenerateDetails->bill_no = $requestGenerate->bill_no;
            $requestGenerateDetails->tax_with_holding = $requestGenerate->tax_with_holding;
            $requestGenerateDetails->date = $requestGenerate->date;
            $requestGenerateDetails->product_code = $request->code[$i];
            $requestGenerateDetails->purchaser_id = $requestGenerate->purchaser_id;
            $requestGenerateDetails->supplier_id = $requestGenerate->supplier_id;
            $requestGenerateDetails->product_id = $request->product_id[$i];
            $requestGenerateDetails->qty = $request->qty[$i];
            $requestGenerateDetails->rate = $request->rate[$i];
            $requestGenerateDetails->excl_val = $request->excl_val[$i];
            $requestGenerateDetails->st_rate = $request->st_rate[$i];
            $requestGenerateDetails->sale_tax = $request->sale_tax[$i];
            $requestGenerateDetails->total = $request->total[$i];
            $requestGenerateDetails->comments = $request->comments[$i];
            $requestGenerateDetails->unit = 1;
            $requestGenerateDetails->created_by = $requestGenerate->created_by;
            $requestGenerateDetails->updated_by = Auth::User()->id;
            $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
            $requestGenerateDetails->status = $requestGenerate->status;
            $requestGenerateDetails->type = $requestGenerate->type;
            $requestGenerateDetails->po = $requestGenerate->po;
            $requestGenerateDetails->po_date = $requestGenerate->po_date;
            $requestGenerateDetails->save();
        }
            return redirect()->back()->with('flash_message', 'Purchase Order Add Successfully!');
        }
        abort(500);
    }

    // public function PrintVoucher(Request $request)
    // {
    //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //     ->where('voucher_name', 'PURCHASE ORDER')
    //     ->where('right_name', 'PRINT')
    //     ->first();
    // if (!$voucherRight) {
    //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    // }
    //     File::cleanDirectory(base_path() . '/upload/purchase-order');
    //     $voucher_no = $request->voucher_no;
    //     $purchaseOrder = RequestGenerate::where('bill_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
    //     // $purchaseOrder = RequestGenerate::where('type', 'Purchase Order')->where('bill_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
    //     if ($purchaseOrder) {
    //         $purchaseOrderDetail = RequestGenerateDetails::with(['purchaseorder' => function ($query) {
    //             $query->with('supplier:id,party_name,address', 'purchaser:id,party_name', 'warehouse:id,name');
    //         }])

    //             ->with('product:id,product_name,uom,code')
    //             // ->where('type', 'Purchase Order')
    //             ->where('bill_no', $voucher_no)
    //             ->where('warehouse_id', Auth::User()->warehouse_id)
    //             ->orderBy('id', 'asc')
    //             ->get();
    //         $pdf = PDF::loadView('purchase-order.invoice-old', compact('purchaseOrderDetail'));
    //         $fileName =  'Purchase-OrderNo' . $voucher_no . '.pdf';
    //         $pdf->save(base_path('upload/purchase-order/' . $fileName));
    //         return $fileName;
    //     }else{
    //         return false;
    //     }
    // }

    public function PrintVoucher(Request $request)
    {
        File::cleanDirectory(base_path() . '/upload/purchase-order');
        $voucher_no = $request->voucher_no;
        $requestGenerate = RequestGenerate::where('bill_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        // $requestGenerate = RequestGenerate::where('type', 'Request Generate')->where('bill_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        if ($requestGenerate) {
            $RequestGenerateDetails = RequestGenerateDetails::with(['request_generate' => function ($query) {
                $query->with('supplier:id,party_name', 'purchaser:id,party_name', 'warehouse:id,name', 'user:id,name', 'updated_by_user:id,name');
            }])

                ->with('product:id,product_name,uom,code')
                ->where('type', 'PO')
                ->where('po', $voucher_no)
                // ->where('bill_no', $voucher_no)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->orderBy('id', 'asc')
                ->get();
            $pdf = PDF::loadView('purchase-order.invoice', compact('RequestGenerateDetails'));
            $fileName =  'RequestGenerateNo' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/purchase-order/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }

    public function editData(Request $request)
    {
        $edit = RequestGenerate::where('po', $request->voucher_no)
        ->where('type', 'PO')
        ->where('warehouse_id', Auth::User()->warehouse_id)
        ->first();

        // $previousrecord = RequestGenerate::where('po', '<', $request->voucher_no)
        // ->where('type', 'PO')
        // ->where('warehouse_id',Auth::User()->warehouse_id)
        // ->max('po');
        // $edit = RequestGenerate::where('bill_no', $request->voucher_no)->whereType('Purchase Order')->where('warehouse_id', Auth::User()->warehouse_id)->first();

        if ($edit) {
            $edit = RequestGenerateDetails::with(['purchaseorder'=>function($qry){
                $qry->with('supplier:id,party_name,address','purchaser:id,party_name');
            }])
                ->with('product:id,product_name,uom,code,tax')
                 ->where('request_generate_id', $edit->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                // ->where('type','Purchase Order')
                ->get();

            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function destroy(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PURCHASE ORDER')
        ->where('right_name', 'DELETE')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        $bill_no = $request->delete_voucher_no;
        // return $RequestGenerate = RequestGenerate::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        $RequestGenerate = RequestGenerate::where('po', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        if ($RequestGenerate) {
            $RequestGenerate->type = "Request Generate";
            $RequestGenerate->request_no = 0;
            $RequestGenerate->po = 0;
            $RequestGenerate->tax_with_holding = 0;
            $RequestGenerate->save();

            $detail = RequestGenerateDetails::where('request_generate_id', $RequestGenerate->id)->get();
            foreach($detail as $data){
                $data->rate = 0;
                $data->excl_val = 0;
                $data->st_rate = 0;
                $data->sale_tax = 0;
                $data->total = 0;
                $data->type = "Request Generate";
                $data->po = 0;
                $RequestGenerate->tax_with_holding = 0;
                $RequestGenerate->po_created_by = null;
                $RequestGenerate->created_at = null;
                $data->save();
            }
           
            // RequestGenerate::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->delete();
            // RequestGenerateDetails::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->delete();
            return redirect()->back()->with('flash_message', 'Purchase Order Deleted Successfully!');
        } else {
            return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        }
    }

    public function LoadNextData(Request $request)
    {
        $nextrecord = RequestGenerate::where('po', '>', $request->voucher_no)
        ->where('type', 'PO')
        ->where('warehouse_id',Auth::User()->warehouse_id)
        ->min('po');

        $reqID = RequestGenerate::where('po', $nextrecord)->first();
        
        // $edit = RequestGenerate::where('po', $request->voucher_no)
        // ->where('type', 'PO')
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        // ->first();
        if ($reqID) {
            $data = RequestGenerateDetails::with(['purchaseorder'=>function($qry){
                $qry->with('supplier:id,party_name,address','purchaser:id,party_name');
            }])
                ->with('product:id,product_name,uom,code,tax')
                //  ->where('po', $nextrecord)
                 ->where('request_generate_id', $reqID->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                // ->where('type','Purchase Order')
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
          $previousrecord = RequestGenerate::where('po', '<', $request->voucher_no)
        ->where('type', 'PO')
        ->where('warehouse_id',Auth::User()->warehouse_id)
        // ->first();
        ->max('po');
        $reqID = RequestGenerate::where('po', $previousrecord)->first();
        if ($reqID) {
              $data = RequestGenerateDetails::with(['purchaseorder'=>function($qry){
                $qry->with('supplier:id,party_name,address','purchaser:id,party_name');
            }])
                ->with('product:id,product_name,uom,code,tax')
                //  ->where('po', $previousrecord)
                 ->where('request_generate_id', $reqID->id)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                // ->where('type','Purchase Order')
                ->get();

            return Response::json(['data' => $data]);
        } else {
            return Response::json(['data' => '']);
        }
       
    }

    public function LoadRequest(Request $request)
    {
        // return $request;
        $previousrecord = RequestGenerate::where('bill_no', '=', $request->voucher_no)
        ->where('warehouse_id',Auth::User()->warehouse_id)
        ->max('bill_no');
        if ($previousrecord) {
        $requestExist = RequestGenerate::where('request_no', '=', $request->voucher_no)
        ->where('warehouse_id',Auth::User()->warehouse_id)
        ->max('request_no');
            if($requestExist){
                // return redirect()->back()->with('access_granted', 'Delete IGP of this Request First!');
                $data = "already exist";
                return Response::json(['data' => $data]);
            }else{
                $data = RequestGenerateDetails::with(['purchaseorder'=>function($qry){
                    $qry->with('supplier:id,party_name,address','purchaser:id,party_name');
                }])
                    ->with('product:id,product_name,uom,code,tax')
                     ->where('bill_no', $previousrecord)
                    ->where('warehouse_id', Auth::User()->warehouse_id)
                    ->where('type','Request Generate')
                    ->get();
    
                return Response::json(['data' => $data]);
            }
            // $data = RequestGenerateDetails::with(['purchaseorder'=>function($qry){
            //     $qry->with('supplier:id,party_name,address','purchaser:id,party_name');
            // }])
            //     ->with('product:id,product_name,uom,code,tax')
            //      ->where('bill_no', $previousrecord)
            //     ->where('warehouse_id', Auth::User()->warehouse_id)
            //     // ->where('type','Purchase Order')
            //     ->get();

            // return Response::json(['data' => $data]);
        
        
        } else {
            return Response::json(['data' => '']);
        }
       
    }

    

}
