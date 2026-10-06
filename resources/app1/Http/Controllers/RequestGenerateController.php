<?php

namespace App\Http\Controllers;

use App\Models\VoucherRights;
use App\Models\Party;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Models\RequestGenerate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use App\Models\RequestGenerateDetails;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\InwardGatePass;
use App\Models\InwardGatePassDetails;

class RequestGenerateController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $requestGenerate = RequestGenerate::where('warehouse_id', Auth::User()->warehouse_id)
        // ->whereType('Request Generate')
        ->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($requestGenerate) {
            $codes = $requestGenerate->bill_no + 1;
        }
        //  $suppliers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, `party_name`, `address`'))
        //     ->where('account_type', 'SUPPLIER')
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Party Name', '');

            $suppliers = Party::select(DB::raw('CONCAT(`id`, "_", `party_name`, "_", `address`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('account_type', 'SUPPLIER')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party Name', '');
        // $purchasers = Party::whereRole('Purchaser')->pluck('party_name', 'id')->prepend('Select Purchaser', '');

        $purchasers = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->whereRole('Purchaser')
            ->OrderBy('id', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Purchaser', '');
        $products = Product::select(DB::raw('CONCAT(`id`, "_", `code`, "_", `product_name`, "_", `uom`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            ->where('warehouse_id', Auth::User()->warehouse_id)
            // ->where('product_type', 'Normal')
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '');
        $warehouse = Warehouse::find(Auth::User()->warehouse_id);

        return view('request-generate.index', compact('codes', 'purchasers', 'suppliers', 'products', 'warehouse'));
    }

    public function store(Request $request)
    {
        $Validator = Validator::make($request->all(), [
            'date' => 'required',
            'bill_no' => 'required',
            'supplier_id' => 'required'
        ], [
            'bill_no.required' => 'The bill number field is required.',
            'supplier_id.required' => 'The supplier field is required.'
        ]);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        if (!isset($request->code) || !isset($request->qty)) {
            return redirect()->back()->with('failure_message', 'Please Enter at least 1 Product');
        }

        $data = $request->all();
        $data['supplier_id'] = explode('_', $request->supplier_id)[0];
        $data['warehouse_id'] = Auth::User()->warehouse_id;

        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'REQUEST GENERATE')
            ->where('right_name', 'EDIT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $requestGenerateFirstRecord = RequestGenerate::where('bill_no', $request->idd)
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->whereType('Request Generate')
            ->first();
            // $requestGenerateFirstRecord = RequestGenerate::whereType('Request Generate')->where('bill_no', $request->idd)->first();
            $requestGenerate = RequestGenerate::find($requestGenerateFirstRecord->id);
            $requestGenerate->update($data);

            RequestGenerateDetails::where('request_generate_id', $requestGenerateFirstRecord->id)
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->whereType('Request Generate')
            ->delete();
            // RequestGenerateDetails::whereType('Request Generate')->where('request_generate_id', $requestGenerateFirstRecord->id)->delete();
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                if($request->qty[$i] >= 1){
                    $requestGenerateDetails = new RequestGenerateDetails();
                $requestGenerateDetails->request_generate_id = $requestGenerate->id;
                $requestGenerateDetails->bill_no = $requestGenerate->bill_no;
                $requestGenerateDetails->date = $requestGenerate->date;
                $requestGenerateDetails->product_code = $request->code[$i];
                $requestGenerateDetails->supplier_id = $requestGenerate->supplier_id;
                $requestGenerateDetails->purchaser_id = $requestGenerate->purchaser_id;
                $requestGenerateDetails->product_id = $request->product_id[$i];
                $requestGenerateDetails->qty = $request->qty[$i];
                $requestGenerateDetails->rate = 0;
                $requestGenerateDetails->excl_val = 0;
                $requestGenerateDetails->st_rate = 0;
                $requestGenerateDetails->sale_tax = 0;
                $requestGenerateDetails->total = 0;
                $requestGenerateDetails->unit = 1;
                $requestGenerateDetails->comments = $request->comments[$i];
                $requestGenerateDetails->created_by = Auth::User()->id;
                $requestGenerateDetails->updated_by = Auth::User()->id;
                $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
                $requestGenerateDetails->status = $requestGenerate->status;
                $requestGenerateDetails->type = $requestGenerate->type;
                $requestGenerateDetails->save();
                }
                
            }

            return redirect()->back()->with('flash_message', 'Request Generate Vouchers Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'REQUEST GENERATE')
            ->where('right_name', 'ADD')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $requestGenerate = RequestGenerate::where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->first();
           $codes = 1;
        if ($requestGenerate) {
            $codes = $requestGenerate->bill_no + 1;
        }
        // return $request;
            $data = $request->all();
            $data['updated_by'] = null;
            $data['bill_no'] = $codes;
            $data['warehouse_id'] = Auth::User()->warehouse_id;
            $requestGenerate = RequestGenerate::create($data);
            $count = count($request->product_id);
            for ($i = 0; $i < $count; $i++) {
                // if($request->qty[$i] >= 1){
                $requestGenerateDetails = new RequestGenerateDetails();
                $requestGenerateDetails->request_generate_id = $requestGenerate->id;
                $requestGenerateDetails->bill_no = $requestGenerate->bill_no;
                $requestGenerateDetails->date = $requestGenerate->date;
                $requestGenerateDetails->product_code = $request->code[$i];
                $requestGenerateDetails->purchaser_id = $requestGenerate->purchaser_id;
                $requestGenerateDetails->supplier_id = $requestGenerate->supplier_id;
                $requestGenerateDetails->product_id = $request->product_id[$i];
                $requestGenerateDetails->qty = $request->qty[$i];
                $requestGenerateDetails->rate = 0;
                $requestGenerateDetails->excl_val = 0;
                $requestGenerateDetails->st_rate = 0;
                $requestGenerateDetails->sale_tax = 0;
                $requestGenerateDetails->total = 0;
                $requestGenerateDetails->unit = 1;
                $requestGenerateDetails->comments = $request->comments[$i];
                $requestGenerateDetails->created_by = Auth::User()->id;
                $requestGenerateDetails->updated_by = Auth::User()->id;
                $requestGenerateDetails->warehouse_id = Auth::User()->warehouse_id;
                $requestGenerateDetails->status = $requestGenerate->status;
                $requestGenerateDetails->type = $requestGenerate->type;
                $requestGenerateDetails->save();
            // }
        }

            return redirect()->back()->with('flash_message', 'Request Generate Successfully!');
        }
        abort(500);
    }

    public function PrintVoucher(Request $request)
    {
        
    //     $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
    //     ->where('voucher_name', 'REQUEST GENERATE')
    //     ->where('right_name', 'PRINT')
    //     ->first();
    // if (!$voucherRight) {
    //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    // }
    // return "enter";
        File::cleanDirectory(base_path() . '/upload/request-generate');
        $voucher_no = $request->voucher_no;
        $requestGenerate = RequestGenerate::where('bill_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        // $requestGenerate = RequestGenerate::where('type', 'Request Generate')->where('bill_no', $voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->first();
        if ($requestGenerate) {
            $RequestGenerateDetails = RequestGenerateDetails::with(['request_generate' => function ($query) {
                $query->with('supplier:id,party_name', 'purchaser:id,party_name', 'warehouse:id,name', 'user:id,name');
            }])

                ->with('product:id,product_name,uom,code')
                ->where('type', 'Request Generate')
                ->where('bill_no', $voucher_no)
                ->where('warehouse_id', Auth::User()->warehouse_id)
                ->orderBy('id', 'asc')
                ->get();
            $pdf = PDF::loadView('request-generate.invoice', compact('RequestGenerateDetails'));
            $fileName =  'RequestGenerateNo' . $voucher_no . '.pdf';
            $pdf->save(base_path('upload/request-generate/' . $fileName));
            return $fileName;
        }else{
            return false;
        }
    }

    public function editData(Request $request)
    {
        $edit = RequestGenerate::with(['request_generate_details' => function ($query) {
            $query->with('product');
            $query->where('type', 'Request Generate');
        }])
            ->with('supplier')
            // ->whereType('Request Generate')
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('bill_no', $request->voucher_no)
            ->orderBy('id', 'asc')
            ->first();
        if ($edit) {
            return Response::json(['data' => $edit]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function destroy(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'REQUEST GENERATE')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $bill_no = $request->delete_voucher_id;
        $RequestGenerate = RequestGenerate::where('bill_no', $bill_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->first();
        $inwardgatepass = InwardGatePass::where('req_gen_id', $RequestGenerate->id)->first();
        //  if ($inwardgatepass) {
        // if ($RequestGenerate) {
        //     return redirect()->back()->with('access_granted', 'Delete IGP of this Request First!');
        // } else {
        //     return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
        // }
        // } else {
        //     RequestGenerate::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->whereType('Request Generate')->delete();
        //     RequestGenerateDetails::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->whereType('Request Generate')->delete();
        //     return redirect()->back()->with('flash_message', 'Request Deleted Successfully!');
        // }

        if ($inwardgatepass) {
            return redirect()->back()->with('access_granted', 'Delete IGP of this Request First!');
        } else {
            if ($RequestGenerate) {
                RequestGenerate::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->whereType('Request Generate')->delete();
                RequestGenerateDetails::where('bill_no', $bill_no)->where('warehouse_id', Auth::User()->warehouse_id)->whereType('Request Generate')->delete();
                return redirect()->back()->with('flash_message', 'Request Deleted Successfully!');
            }else{
                return redirect()->back()->with('failure_message', 'Voucher No Not Exist!');
            }
        }
    }

    public function LoadNextData(Request $request)
    {
        // $id = RequestGenerate::where('bill_no', '>', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->min('id');
        $id = RequestGenerate::where('bill_no', '>', $request->voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->min('id');
        $RequestGenerate = RequestGenerate::with(['request_generate_details' => function ($query) {
            $query->with('product');
            $query->where('type', 'Request Generate');
        }])
            ->with('supplier')
            // ->whereType('Request Generate')
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->whereId($id)
            ->orderBy('id', 'asc')
            ->first();

        if ($id != 0 || $id != null) {
            return Response::json(['data' => $RequestGenerate]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    public function LoadPreviousData(Request $request)
    {
        $id = RequestGenerate::where('bill_no', '<', $request->voucher_no)->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        // $id = RequestGenerate::where('bill_no', '<', $request->voucher_no) ->whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->max('id');
        $RequestGenerate = RequestGenerate::with(['request_generate_details' => function ($query) {
            $query->with('product');
            $query->where('type', 'Request Generate');
        }])
            ->with('supplier')
            // ->whereType('Request Generate')
            ->where('warehouse_id', Auth::User()->warehouse_id)
            ->whereId($id)
            
            ->orderBy('id', 'asc')
            ->first();

        if ($id != 0 || $id != null) {
            return Response::json(['data' => $RequestGenerate]);
        } else {
            return Response::json(['data' => '']);
        }
    }

    // public function report(Request $request)
    // {
       
    //     if ($request->ajax()) {
    //         $supplier = '';
    //         $purchaser = '';
    //         $fromDate = $request->from_date;
    //         $toDate = $request->to_date;
    //         $product_id = $request->product_id;
    //         // $supplier_id = $request->supplier_id;
    //         $purchaser_id = $request->purchaser_id;
    //         //  return $request;
    //         // if ($supplier_id != 0) {
    //         //     $supplier = Party::find($supplier_id);
    //         // }
    //         if ($purchaser_id != 0) {
    //             $purchaser = Party::find($purchaser_id);
    //         }


    //         if ($request->report_type == 'request_generate') {
    //             if ($product_id != 0) {
    //                 $ReqGeneReportSummary = RequestGenerateDetails::join('products', 'products.id', '=', 'request_generate_details.product_id')
    //                     //  ->join('parties as supplier', 'supplier.id', '=', 'request_generate_details.supplier_id')
    //                     // ->join('parties as purchaser', 'purchaser.id', '=', 'request_generate_details.purchaser_id')
    //                     ->select(
    //                         'request_generate_details.date as date',
    //                         'request_generate_details.bill_no',
    //                         'products.product_name',
    //                         'products.uom as uom',
    //                         DB::raw('SUM(request_generate_details.qty) as qty')
    //                     )
    //                     ->whereDate('request_generate_details.date', '>=', $fromDate)
    //                     ->whereDate('request_generate_details.date', '<=', $toDate)
    //                     ->where('request_generate_details.product_id', $product_id)
    //                     // ->where(function($query) use ($supplier_id)  {
    //                     //     if($supplier_id!=0) {
    //                     //         $query ->where('request_generate_details.supplier_id', $supplier_id);
    //                     //     }
    //                     //  })
    //                     ->where(function ($queryy) use ($purchaser_id) {
    //                         if ($purchaser_id != 0) {
    //                             $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
    //                         }
    //                     })
    //                     ->groupBy('request_generate_details.product_id')
    //                     ->orderBy('request_generate_details.date', 'asc')
    //                     ->get();
    //                 return Response::json(['data' => $ReqGeneReportSummary, 'purchaser' => $purchaser]);
    //                 // return Response::json(['data' => $ReqGeneReportSummary, 'supplier' => $supplier,'purchaser'=>$purchaser]);
    //             } else {
    //                 //  return "below";
    //                 $ReqGeneReportDetail = RequestGenerateDetails::with('product:id,product_name,uom')
    //                     ->whereDate('date', '>=', $fromDate)
    //                     ->whereDate('date', '<=', $toDate)
    //                     // ->where('supplier_id', $supplier_id)
    //                     // ->Where('purchaser_id', $purchaser_id)
    //                     ->orderBy('date', 'asc')
    //                     ->get();

    //                 return Response::json(['data' => $ReqGeneReportDetail, 'purchaser' => $purchaser]);
    //                 // return Response::json(['data' =>$ReqGeneReportDetail, 'supplier' => $supplier,'purchaser'=>$purchaser]);
    //             }
    //         }
    //         else
    //         if ($request->report_type == 'inward_gatepass') {
    //             if ($product_id != 0) {
    //                 $ReqGeneReportSummary = InwardGatePassDetails::join('products', 'products.id', '=', 'inward_gate_pass_details.product_id')
    //                     ->select(
    //                         'inward_gate_pass_details.date as date',
    //                         'inward_gate_pass_details.bill_no',
    //                         'products.product_name',
    //                         'products.uom as uom',
    //                         DB::raw('SUM(inward_gate_pass_details.qty) as qty')
    //                     )
    //                     ->whereDate('inward_gate_pass_details.date', '>=', $fromDate)
    //                     ->whereDate('inward_gate_pass_details.date', '<=', $toDate)
    //                     ->where('inward_gate_pass_details.product_id', $product_id)
    
    //                     ->where(function ($queryy) use ($purchaser_id) {
    //                         if ($purchaser_id != 0) {
    //                             $queryy->where('inward_gate_pass_details.purchaser_id', $purchaser_id);
    //                         }
    //                     })
    //                     ->groupBy('inward_gate_pass_details.product_id')
    //                     ->orderBy('inward_gate_pass_details.date', 'asc')
    //                     ->get();
    //                 return Response::json(['data' => $ReqGeneReportSummary, 'purchaser' => $purchaser]);
    //             } else {
    //                 $ReqGeneReportDetail = InwardGatePassDetails::with('product:id,product_name,uom')
    //                     ->whereDate('date', '>=', $fromDate)
    //                     ->whereDate('date', '<=', $toDate)
    //                     ->orderBy('date', 'asc')
    //                     ->get();
    
    //                 return Response::json(['data' => $ReqGeneReportDetail, 'purchaser' => $purchaser]);
    //             }
    //         }
    //     }
        
    //     $products = Product::pluck('product_name', 'id')->prepend('Select Product', 0);
    //     $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('Select Supplier', 0);
    //     $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('Select Purchaser', 0);
    //     return view('request-generate.report', compact('products', 'supplier', 'purchaser'));
    // }


    public function allRequestGenerate(Request $request)
    {
        if ($request->ajax()) {
            $allRequestGenerate = RequestGenerate::whereType('Request Generate')->where('warehouse_id', Auth::User()->warehouse_id)->orderBy('id', 'desc')->get();
            return DataTables::of($allRequestGenerate)
                ->addIndexColumn()
                ->editColumn('warehouse_id', function ($data) {
                    if ($data->warehouse) {
                        return $data->warehouse->name;
                    }
                })
                ->editColumn('supplier_id', function ($data) {
                    if ($data->supplier) {
                        return $data->supplier->party_name;
                    }
                })
                ->editColumn('purchaser_id', function ($data) {
                    if ($data->purchaser) {
                        return $data->purchaser->party_name;
                    }
                })
                ->editColumn('status', function ($data) {
                    if ($data->status == 0) {
                        return $data->status = 'In Process';
                    } else {
                        return $data->status = 'Completed';
                    }
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm print_btn" req_id="' . $row->id . '_' . $row->bill_no . '">
                    <i class="fa fa-print"></i></button>&nbsp;</div>';


                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('request-generate.all-req-generate');
    }
}
