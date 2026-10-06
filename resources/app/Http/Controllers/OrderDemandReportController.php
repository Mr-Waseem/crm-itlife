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

class OrderDemandReportController extends Controller
{
    public function report(Request $request)
    {
    // return $request;
        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $party_id = $request->party_id;
            $DCType = $request->OrderType;
           $reportType = $request->report_type;
            if ($reportType == 'summary') {
             $summaryReport1 = SaleOrder::
            // with('product:id,code,product_name')->with('party:id,code,party_name')
            // ->with('sale_order:id,voucher_no,voucher_date,po_date,po_no')
                with(['sale_order_details' => function($query){
                $query->with('product:id,code,product_name');
                $query->with('party:id,party_name');
                $query->with('dc_details2');
            }])
            ->where(function ($query) use ($party_id) {
                if ($party_id != 0) {
                    $query->where('party_id', $party_id);
                }
            })
            ->where(function ($query) use ($DCType) {
                if ($DCType != 0) {
                    $query->where('type', $DCType);
                }
            })
            ->whereDate('voucher_date', '>=', $fromDate)
            ->whereDate('voucher_date', '<=', $toDate)
            // ->groupBy('sale_order_details.sale_order_id')
            // ->orderBy('parties.id')
            ->orderBy('voucher_no')
            ->orderBy('voucher_date')
            ->get();
            // return "dfsds";
            $summaryReport = [];
            foreach ($summaryReport1 as $data2) {
                $totalDespatch = 0; $totalOrderQty = 0;
                foreach ($data2->sale_order_details as $data1) {
                    $totalOrderQty = $totalOrderQty + $data1->order_qty;
                    foreach ($data1->dc_details2 as $data) {
                        $totalDespatch = $totalDespatch + $data->sale_qty;
                    }
                }
                    $summaryReport[] = [
                    // 'ONEid' => $data1->id,
                    'voucher_no' => $data2->voucher_no,
                    'voucher_date' => $data2->voucher_date,
                    'type' => $data2->type,
                    'party_name' => $data1->party->party_name,
                    'code' => $data1->product->code,
                    'product_name' => $data1->product->product_name,
                    'po_date' => $data2->po_date,
                    'po_no' => $data2->po_no,
                    'OrderQty' => $totalOrderQty,
                    'DespatchQty' => $totalDespatch,
                    'remarks' => $data2->remarks,
                ];
            }
            return $summaryReport;
            }
            if ($reportType == 'detailed') {
            $summaryReport1 = SaleOrderDetails::with('product:id,code,product_name')->with('party:id,code,party_name')
            ->with('sale_order:id,voucher_no,voucher_date,po_date,po_no')
            ->with('dc_details2')
            ->where(function ($query) use ($party_id) {
                if ($party_id != 0) {
                    $query->where('party_id', $party_id);
                }
            })
            ->where(function ($query) use ($DCType) {
                if ($DCType != 0) {
                    $query->where('type', $DCType);
                }
            })
            ->whereDate('voucher_date', '>=', $fromDate)
            ->whereDate('voucher_date', '<=', $toDate)
            // ->groupBy('sale_order_details.sale_order_id')
            // ->orderBy('parties.id')
            ->orderBy('voucher_no')
            ->orderBy('voucher_date')
            ->get();
            // return "dfsds";
            $summaryReport = [];
            foreach ($summaryReport1 as $data1) {
                $totalDespatch = 0; 
                foreach ($data1->dc_details2 as $data) {
                    $totalDespatch = $totalDespatch + $data->sale_qty;
                }
                    $summaryReport[] = [
                    // 'ONEid' => $data1->id,
                    'voucher_no' => $data1->voucher_no,
                    'voucher_date' => $data1->voucher_date,
                    'type' => $data1->type,
                    'party_name' => $data1->party->party_name,
                    'code' => $data1->product->code,
                    'product_name' => $data1->product->product_name,
                    'po_date' => $data1->sale_order->po_date,
                    'po_no' => $data1->sale_order->po_no,
                    'OrderQty' => $data1->order_qty,
                    'DespatchQty' => $totalDespatch,
                    'remarks' => $data1->remarks,
                ];
            }
            return $summaryReport;
            // return response()->json(['data' => $summaryReport]);
            }
        }
        $customers = Party::join('account_groups', 'account_groups.id', '=', 'parties.account_group_id')
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            // ->where('parties.account_group_id', '1')
            // ->Orwhere('parties.account_group_id', '7')
            ->where('role', '=', 'Customer')
            ->OrderBy('party_name', 'asc')
            ->pluck('parties.party_name', 'parties.id')
            ->prepend('All Parties', '0');
        $type = ['0' => 'All ORDER | DEMANDS', 'SALE DEMAND' => 'SALE DEMAND', 'SALE ORDER' => 'SALE ORDER'];

        return view('sales-demand.report.index', compact('customers', 'type'));
    }

    public function PrintReport(Request $request)
    {
        // return $request;
        // $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        //     ->where('voucher_name', 'SALE DEMAND')
        //     ->where('right_name', 'PRINT')
        //     ->first();
        File::cleanDirectory(base_path() . '/upload/sales-demand');
        $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $party_id = $request->party_id;
            $DCType = $request->OrderType;
           $reportType = $request->report_type;
           if ($reportType == 'summary') {
            // return "sumary";
            $summaryReport1 = SaleOrder::
            // with('product:id,code,product_name')->with('party:id,code,party_name')
            // ->with('sale_order:id,voucher_no,voucher_date,po_date,po_no')
                with(['sale_order_details' => function($query){
                $query->with('product:id,code,product_name');
                $query->with('party:id,party_name');
                $query->with('dc_details2');
            }])
            ->where(function ($query) use ($party_id) {
                if ($party_id != 0) {
                    $query->where('party_id', $party_id);
                }
            })
            ->where(function ($query) use ($DCType) {
                if ($DCType != 0) {
                    $query->where('type', $DCType);
                }
            })
            ->whereDate('voucher_date', '>=', $fromDate)
            ->whereDate('voucher_date', '<=', $toDate)
            // ->groupBy('sale_order_details.sale_order_id')
            // ->orderBy('parties.id')
            ->orderBy('voucher_no')
            ->orderBy('voucher_date')
            ->get();

            // return "dfsds";
            $summaryReport = [];
            foreach ($summaryReport1 as $data2) {
                $totalDespatch = 0; $totalOrderQty = 0;
                foreach ($data2->sale_order_details as $data1) {
                    $totalOrderQty = $totalOrderQty + $data1->order_qty;
                    foreach ($data1->dc_details2 as $data) {
                        $totalDespatch = $totalDespatch + $data->sale_qty;
                    }
                }
                    $summaryReport[] = [
                    // 'ONEid' => $data1->id,
                    'voucher_no' => $data2->voucher_no,
                    'voucher_date' => $data2->voucher_date,
                    'type' => $data2->type,
                    'party_name' => $data1->party->party_name,
                    'code' => $data1->product->code,
                    'product_name' => $data1->product->product_name,
                    'po_date' => $data2->po_date,
                    'po_no' => $data2->po_no,
                    'OrderQty' => $totalOrderQty,
                    'DespatchQty' => $totalDespatch,
                    'remarks' => $data2->remarks,
                ];
            }
            $pdf = PDF::loadView('sales-demand.report.summary', compact('summaryReport', 'fromDate', 'toDate', 'reportType'))->setPaper('a4', 'Landscape');
                $fileName =  'Sales Demand Report (Summary).pdf';
                $pdf->save(base_path('upload/sales-demand/' . $fileName));
                return $fileName;
        }
        if ($reportType == 'detailed') {
            $summaryReport1 = SaleOrderDetails::with('product:id,code,product_name')->with('party:id,code,party_name')
            ->with('sale_order:id,voucher_no,voucher_date,po_date,po_no')
            ->with('dc_details2')
            ->where(function ($query) use ($party_id) {
                if ($party_id != 0) {
                    $query->where('party_id', $party_id);
                }
            })
            ->where(function ($query) use ($DCType) {
                if ($DCType != 0) {
                    $query->where('type', $DCType);
                }
            })
            ->whereDate('voucher_date', '>=', $fromDate)
            ->whereDate('voucher_date', '<=', $toDate)
            // ->groupBy('sale_order_details.sale_order_id')
            // ->orderBy('parties.id')
            ->orderBy('voucher_no')
            ->orderBy('voucher_date')
            ->get();
            // return "dfsds";
            $summaryReport = [];
            foreach ($summaryReport1 as $data1) {
                $totalDespatch = 0; 
                foreach ($data1->dc_details2 as $data) {
                    $totalDespatch = $totalDespatch + $data->sale_qty;
                }
                    $summaryReport[] = [
                    // 'ONEid' => $data1->id,
                    'voucher_no' => $data1->voucher_no,
                    'voucher_date' => $data1->voucher_date,
                    'type' => $data1->type,
                    'party_name' => $data1->party->party_name,
                    'code' => $data1->product->code,
                    'product_name' => $data1->product->product_name,
                    'po_date' => $data1->sale_order->po_date,
                    'po_no' => $data1->sale_order->po_no,
                    'OrderQty' => $data1->order_qty,
                    'DespatchQty' => $totalDespatch,
                    'remarks' => $data1->remarks,
                ];
            }
                // return $summaryReport;
            $pdf = PDF::loadView('sales-demand.report.summary', compact('summaryReport', 'fromDate', 'toDate', 'reportType'))->setPaper('a4', 'Landscape');
            $fileName =  'Sales Demand Report (Summary).pdf';
            $pdf->save(base_path('upload/sales-demand/' . $fileName));
            return $fileName;
        }
    }
}
