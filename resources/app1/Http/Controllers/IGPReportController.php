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

class IGPReportController extends Controller
{
    public function index(Request $request)
    {
        
            $warehouses = Warehouse::pluck('name', 'id')->prepend('All Warehouses', 0);
            $products = Product::pluck('product_name', 'id')->prepend('All Products', 0);
            $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('All Suppliers', 0);
            $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('All Purchasers', 0);
            return view('inward-gatepass.report.index', compact('products', 'supplier', 'purchaser', 'warehouses'));
        
    }

    public function report(Request $request)
    {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->productID;
            // $supplier_id = $request->supplier_id;
            $purchaser_id = $request->purchaserID;
            $supplier_id = $request->supplierID;
            $reportType = $request->report_type;
            $WarehouseID = $request->warehouseID;

            if ($reportType == 'summary') {
                // return "dfsfds";
                // if ($product_id != 0) {
                        $IGPSummary = InwardGatePassDetails::join('inward_gate_passes', 'inward_gate_passes.id', '=', 'inward_gate_pass_details.inward_gatepass_id')
                       ->join('request_generates', 'request_generates.id', '=', 'inward_gate_passes.req_gen_id')
                       ->join('warehouses', 'warehouses.id', '=', 'inward_gate_passes.warehouse_id')
                       ->join('parties as supplier', 'supplier.id', '=', 'inward_gate_passes.supplier_id')
                       ->join('parties as purchaser', 'purchaser.id', '=', 'inward_gate_passes.purchaser_id')
                        ->select(
                        'inward_gate_passes.date as date',
                        'inward_gate_passes.bill_no',
                        'warehouses.name as warehouse_name',
                        // 'request_generates.bill_no as request_no',
                        'request_generates.bill_no as request_no',
                        'supplier.party_name as supplier_name',
                        'purchaser.party_name as purchaser_name',
                        // 'inward_gate_pass_details.qty as qty',
                        // DB::raw('SUM(inward_gate_pass_details.qty) as qty')
                        DB::raw('SUM(inward_gate_pass_details.qty) as qty')
                    )
                       ->whereDate('inward_gate_pass_details.date', '>=', $fromDate)
                        ->whereDate('inward_gate_pass_details.date', '<=', $toDate)
                        ->where(function ($queryy) use ($WarehouseID) {
                            if ($WarehouseID != 0) {
                                $queryy->where('inward_gate_pass_details.warehouse_id', $WarehouseID);
                            }
                        })
                        ->where(function ($queryy) use ($supplier_id) {
                            if ($supplier_id != 0) {
                                $queryy->where('inward_gate_pass_details.supplier_id', $supplier_id);
                            }
                        })
                        ->where(function ($queryy) use ($purchaser_id) {
                            if ($purchaser_id != 0) {
                                $queryy->where('inward_gate_pass_details.purchaser_id', $purchaser_id);
                            }
                        })
                        ->where(function ($queryy) use ($product_id) {
                            if ($product_id != 0) {
                                $queryy->where('inward_gate_pass_details.product_id', $product_id);
                            }
                        })
                        // ->where('inward_gate_pass_details.type', 'Request Generate')
                        ->groupBy('inward_gate_pass_details.bill_no')
                        ->orderBy('inward_gate_pass_details.bill_no', 'asc')
                        ->get();
                    return Response::json(['data' => $IGPSummary]);

            }
            else
            if ($reportType == 'detail') {
                $IGPSummary = InwardGatePassDetails::join('inward_gate_passes', 'inward_gate_passes.id', '=', 'inward_gate_pass_details.inward_gatepass_id')
                ->join('products', 'products.id', '=', 'inward_gate_pass_details.product_id')
                ->join('request_generates', 'request_generates.id', '=', 'inward_gate_passes.req_gen_id')
                ->join('warehouses', 'warehouses.id', '=', 'inward_gate_passes.warehouse_id')
                ->join('parties as supplier', 'supplier.id', '=', 'inward_gate_passes.supplier_id')
                ->join('parties as purchaser', 'purchaser.id', '=', 'inward_gate_passes.purchaser_id')
                
                 ->select(
                 'inward_gate_passes.date as date',
                 'inward_gate_passes.bill_no',
                 'warehouses.name as warehouse_name',
                //  'request_generates.bill_no as request_no',
                 'inward_gate_pass_details.request_no as request_no',
                 'supplier.party_name as supplier_name',
                 'purchaser.party_name as purchaser_name',
                 'products.code as product_code',
                 'products.product_name as product_name',
                 'inward_gate_pass_details.qty as qty',
                 'inward_gate_passes.vehicle_no',
                 'inward_gate_passes.transport_company',
                 'inward_gate_passes.driver_name',
                 'inward_gate_passes.builty_no',
                 'inward_gate_passes.driver_phoneno',
                 
                 // DB::raw('SUM(inward_gate_pass_details.qty) as qty')
                //  DB::raw('SUM(inward_gate_pass_details.qty) as qty')
             )
                ->whereDate('inward_gate_pass_details.date', '>=', $fromDate)
                 ->whereDate('inward_gate_pass_details.date', '<=', $toDate)
                 ->where(function ($queryy) use ($WarehouseID) {
                     if ($WarehouseID != 0) {
                         $queryy->where('inward_gate_pass_details.warehouse_id', $WarehouseID);
                     }
                 })
                 ->where(function ($queryy) use ($supplier_id) {
                     if ($supplier_id != 0) {
                         $queryy->where('inward_gate_pass_details.supplier_id', $supplier_id);
                     }
                 })
                 ->where(function ($queryy) use ($purchaser_id) {
                     if ($purchaser_id != 0) {
                         $queryy->where('inward_gate_pass_details.purchaser_id', $purchaser_id);
                     }
                 })
                 ->where(function ($queryy) use ($product_id) {
                     if ($product_id != 0) {
                         $queryy->where('inward_gate_pass_details.product_id', $product_id);
                     }
                 })
                 // ->where('inward_gate_pass_details.type', 'Request Generate')
                //  ->groupBy('inward_gate_pass_details.bill_no')
                 ->orderBy('inward_gate_pass_details.bill_no', 'asc')
                 ->orderBy('inward_gate_pass_details.id', 'asc')
                 ->get();
             return Response::json(['data' => $IGPSummary]);

            }
    }

    public function printPDF(Request $request){
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $product_id = $request->productID;
        // $supplier_id = $request->supplier_id;
        $purchaser_id = $request->purchaserID;
        $supplier_id = $request->supplierID;
        $reportType = $request->report_type;
        $WarehouseID = $request->warehouseID;

         $supplier = Party::where('id', $supplier_id)->first(['code', 'party_name']);
         $purchaser = Party::where('id', $purchaser_id)->first(['code', 'party_name']);
        $product = Product::where('id', $product_id)->first(['code', 'product_name']);
       $warehousName = Warehouse::where('id', $WarehouseID)->first(['name']);
        //  return $request;
        // if ($supplier_id != 0) {
        //     $supplier = Party::find($supplier_id);
        // }
        // if ($purchaser_id != 0) {
        //     $purchaser = Party::find($purchaser_id);
        // }
        if ($reportType == 'summary') {
            // return "dfsfds";
            // if ($product_id != 0) {
                $IGPSummary = InwardGatePassDetails::join('inward_gate_passes', 'inward_gate_passes.id', '=', 'inward_gate_pass_details.inward_gatepass_id')
                ->join('request_generates', 'request_generates.id', '=', 'inward_gate_passes.req_gen_id')
                ->join('warehouses', 'warehouses.id', '=', 'inward_gate_passes.warehouse_id')
                ->join('parties as supplier', 'supplier.id', '=', 'inward_gate_passes.supplier_id')
                ->join('parties as purchaser', 'purchaser.id', '=', 'inward_gate_passes.purchaser_id')
                 ->select(
                 'inward_gate_passes.date as date',
                 'inward_gate_passes.bill_no',
                 'warehouses.name as warehouse_name',
                 'request_generates.bill_no as request_no',
                 'supplier.party_name as supplier_name',
                 'purchaser.party_name as purchaser_name',
                 // 'inward_gate_pass_details.qty as qty',
                 // DB::raw('SUM(inward_gate_pass_details.qty) as qty')
                 DB::raw('SUM(inward_gate_pass_details.qty) as qty')
             )
                ->whereDate('inward_gate_pass_details.date', '>=', $fromDate)
                 ->whereDate('inward_gate_pass_details.date', '<=', $toDate)
                 ->where(function ($queryy) use ($WarehouseID) {
                     if ($WarehouseID != 0) {
                         $queryy->where('inward_gate_pass_details.warehouse_id', $WarehouseID);
                     }
                 })
                 ->where(function ($queryy) use ($supplier_id) {
                     if ($supplier_id != 0) {
                         $queryy->where('inward_gate_pass_details.supplier_id', $supplier_id);
                     }
                 })
                 ->where(function ($queryy) use ($purchaser_id) {
                     if ($purchaser_id != 0) {
                         $queryy->where('inward_gate_pass_details.purchaser_id', $purchaser_id);
                     }
                 })
                 ->where(function ($queryy) use ($product_id) {
                     if ($product_id != 0) {
                         $queryy->where('inward_gate_pass_details.product_id', $product_id);
                     }
                 })
                 // ->where('inward_gate_pass_details.type', 'Request Generate')
                 ->groupBy('inward_gate_pass_details.bill_no')
                 ->orderBy('inward_gate_pass_details.bill_no', 'asc')
                 ->get();

                $pdf = PDF::loadView('inward-gatepass.report.summary', compact('IGPSummary', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product', 'warehousName'));
                $fileName =  'IGP Report Summary.pdf';
                $pdf->save(base_path('upload/inward-gatepass/' . $fileName));
                return $fileName;
                // return Response::json(['data' => $ReqGeneReportSummary]);

        }
        else
        if ($reportType == 'detail') {
            // return "detail";
            $IGPSummary = InwardGatePassDetails::join('inward_gate_passes', 'inward_gate_passes.id', '=', 'inward_gate_pass_details.inward_gatepass_id')
                ->join('products', 'products.id', '=', 'inward_gate_pass_details.product_id')
                ->join('request_generates', 'request_generates.id', '=', 'inward_gate_passes.req_gen_id')
                ->join('warehouses', 'warehouses.id', '=', 'inward_gate_passes.warehouse_id')
                ->join('parties as supplier', 'supplier.id', '=', 'inward_gate_passes.supplier_id')
                ->join('parties as purchaser', 'purchaser.id', '=', 'inward_gate_passes.purchaser_id')
                
                 ->select(
                 'inward_gate_passes.date as date',
                 'inward_gate_passes.bill_no',
                 'warehouses.name as warehouse_name',
                //  'request_generates.bill_no as request_no',
                 'inward_gate_pass_details.request_no as request_no',
                 'supplier.party_name as supplier_name',
                 'purchaser.party_name as purchaser_name',
                 'products.code as product_code',
                 'products.product_name as product_name',
                 'inward_gate_pass_details.qty as qty',
                 'inward_gate_passes.vehicle_no',
                 'inward_gate_passes.transport_company',
                 'inward_gate_passes.driver_name',
                 'inward_gate_passes.builty_no',
                 'inward_gate_passes.driver_phoneno',
                 
                 // DB::raw('SUM(inward_gate_pass_details.qty) as qty')
                //  DB::raw('SUM(inward_gate_pass_details.qty) as qty')
             )
                ->whereDate('inward_gate_pass_details.date', '>=', $fromDate)
                 ->whereDate('inward_gate_pass_details.date', '<=', $toDate)
                 ->where(function ($queryy) use ($WarehouseID) {
                     if ($WarehouseID != 0) {
                         $queryy->where('inward_gate_pass_details.warehouse_id', $WarehouseID);
                     }
                 })
                 ->where(function ($queryy) use ($supplier_id) {
                     if ($supplier_id != 0) {
                         $queryy->where('inward_gate_pass_details.supplier_id', $supplier_id);
                     }
                 })
                 ->where(function ($queryy) use ($purchaser_id) {
                     if ($purchaser_id != 0) {
                         $queryy->where('inward_gate_pass_details.purchaser_id', $purchaser_id);
                     }
                 })
                 ->where(function ($queryy) use ($product_id) {
                     if ($product_id != 0) {
                         $queryy->where('inward_gate_pass_details.product_id', $product_id);
                     }
                 })
                 // ->where('inward_gate_pass_details.type', 'Request Generate')
                //  ->groupBy('inward_gate_pass_details.bill_no')
                 ->orderBy('inward_gate_pass_details.bill_no', 'asc')
                 ->orderBy('inward_gate_pass_details.id', 'asc')
                 ->get();
           $pdf = PDF::loadView('inward-gatepass.report.detail', compact('IGPSummary', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product', 'warehousName'))->setPaper('a4', 'landscape');
           $fileName =  'IGP Report Detail.pdf';
           $pdf->save(base_path('upload/inward-gatepass/' . $fileName));
           return $fileName;
        }
    }
}
