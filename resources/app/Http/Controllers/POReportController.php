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

class POReportController extends Controller
{
    public function index(Request $request)
    {
         
            
            $products = Product::pluck('product_name', 'id')->prepend('All Products', 0);
            $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('All Suppliers', 0);
            $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('All Purchasers', 0);
            return view('request-generate.po-report.index', compact('products', 'supplier', 'purchaser'));
        
    }

    public function report(Request $request)
    {
       

        // return $request;
        // if ($request->ajax()) {

            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->productID;
            // $supplier_id = $request->supplier_id;
            $purchaser_id = $request->purchaserID;
            $supplier_id = $request->supplierID;
            $reportType = $request->report_type;
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
                    $ReqGeneReportSummary = RequestGenerateDetails::join('products', 'products.id', '=', 'request_generate_details.product_id')
                         ->join('parties as supplier', 'supplier.id', '=', 'request_generate_details.supplier_id')
                        ->join('parties as purchaser', 'purchaser.id', '=', 'request_generate_details.purchaser_id')
                        ->select(
                            'request_generate_details.po_date as date',
                            'request_generate_details.bill_no',
                            'request_generate_details.po as po',
                            'supplier.party_name as supplier_name',
                            'purchaser.party_name as purchaser_name',
                            'products.code',
                            'products.product_name',
                            'products.uom as uom',
                            DB::raw('SUM(request_generate_details.qty) as qty')
                        )
                        ->whereDate('request_generate_details.po_date', '>=', $fromDate)
                        ->whereDate('request_generate_details.po_date', '<=', $toDate)
                        ->where('request_generate_details.type', 'PO')
                        // ->where('request_generate_details.type', 'Request Generate')
                        // ->where('request_generate_details.product_id', $product_id)
                        ->where(function ($queryy) use ($supplier_id) {
                            if ($supplier_id != 0) {
                                $queryy->where('request_generate_details.supplier_id', $supplier_id);
                            }
                        })
                        ->where(function ($queryy) use ($purchaser_id) {
                            if ($purchaser_id != 0) {
                                $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
                            }
                        })
                        ->where(function ($queryy) use ($product_id) {
                            if ($product_id != 0) {
                                $queryy->where('request_generate_details.product_id', $product_id);
                            }
                        })
                        // ->groupBy('request_generate_details.product_id')
                        ->groupBy('request_generate_details.bill_no')
                        ->orderBy('request_generate_details.bill_no', 'asc')
                        ->get();
                    return Response::json(['data' => $ReqGeneReportSummary]);

            }
            else
            if ($reportType == 'detail') {
                // return "detail";
                $ReqGeneReportSummary = RequestGenerateDetails::join('products', 'products.id', '=', 'request_generate_details.product_id')
                ->join('parties as supplier', 'supplier.id', '=', 'request_generate_details.supplier_id')
               ->join('parties as purchaser', 'purchaser.id', '=', 'request_generate_details.purchaser_id')
               ->select(
                   'request_generate_details.po_date as date',
                   'request_generate_details.bill_no',
                   'request_generate_details.po as po',
                   'supplier.party_name as supplier_name',
                   'purchaser.party_name as purchaser_name',
                   'products.code',
                   'products.product_name',
                   'products.uom as uom',
                   'request_generate_details.qty as qty',
                //    DB::raw('SUM(request_generate_details.qty) as qty')
               )
               ->whereDate('request_generate_details.po_date', '>=', $fromDate)
               ->whereDate('request_generate_details.po_date', '<=', $toDate)
               ->where('request_generate_details.type', 'PO')
            //    ->where('request_generate_details.type', 'Request Generate')
               // ->where('request_generate_details.product_id', $product_id)
               ->where(function ($queryy) use ($supplier_id) {
                   if ($supplier_id != 0) {
                       $queryy->where('request_generate_details.supplier_id', $supplier_id);
                   }
               })
               ->where(function ($queryy) use ($purchaser_id) {
                   if ($purchaser_id != 0) {
                       $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
                   }
               })
               ->where(function ($queryy) use ($product_id) {
                   if ($product_id != 0) {
                       $queryy->where('request_generate_details.product_id', $product_id);
                   }
               })
            //    ->groupBy('request_generate_details.product_id')
            //    ->groupBy('request_generate_details.bill_no')
               ->orderBy('request_generate_details.bill_no', 'asc')
               ->get();
           return Response::json(['data' => $ReqGeneReportSummary]);
            }
    }

    public function printPDF(Request $request){
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $product_id = $request->productID;
        // $supplier_id = $request->supplier_id;
        $purchaser_id = $request->purchaserID;
        $supplier_id = $request->supplierID;
        $reportType = $request->report_type;

         $supplier = Party::where('id', $supplier_id)->first(['code', 'party_name']);
         $purchaser = Party::where('id', $purchaser_id)->first(['code', 'party_name']);
        $product = Product::where('id', $product_id)->first(['code', 'product_name']);
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
                $ReqGeneReportSummary = RequestGenerateDetails::join('products', 'products.id', '=', 'request_generate_details.product_id')
                     ->join('parties as supplier', 'supplier.id', '=', 'request_generate_details.supplier_id')
                    ->join('parties as purchaser', 'purchaser.id', '=', 'request_generate_details.purchaser_id')
                    ->select(
                        'request_generate_details.po_date as date',
                        'request_generate_details.bill_no',
                        'request_generate_details.po as po',
                        'supplier.party_name as supplier_name',
                        'purchaser.party_name as purchaser_name',
                        'products.code',
                        'products.product_name',
                        'products.uom as uom',
                        DB::raw('SUM(request_generate_details.qty) as qty')
                    )
                    ->whereDate('request_generate_details.po_date', '>=', $fromDate)
                    ->whereDate('request_generate_details.po_date', '<=', $toDate)
                    ->where('request_generate_details.type', 'PO')
                    // ->where('request_generate_details.type', 'Request Generate')
                    // ->where('request_generate_details.product_id', $product_id)
                    ->where(function ($queryy) use ($supplier_id) {
                        if ($supplier_id != 0) {
                            $queryy->where('request_generate_details.supplier_id', $supplier_id);
                        }
                    })
                    ->where(function ($queryy) use ($purchaser_id) {
                        if ($purchaser_id != 0) {
                            $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
                        }
                    })
                    ->where(function ($queryy) use ($product_id) {
                        if ($product_id != 0) {
                            $queryy->where('request_generate_details.product_id', $product_id);
                        }
                    })
                    // ->groupBy('request_generate_details.product_id')
                    ->groupBy('request_generate_details.bill_no')
                    ->orderBy('request_generate_details.bill_no', 'asc')
                    ->get();

                $pdf = PDF::loadView('request-generate.po-report.summary', compact('ReqGeneReportSummary', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product'));
                $fileName =  'PO Report Summary.pdf';
                $pdf->save(base_path('upload/request-generate/' . $fileName));
                return $fileName;
                // return Response::json(['data' => $ReqGeneReportSummary]);

        }
        else
        if ($reportType == 'detail') {
            // return "detail";
            $ReqGeneReportSummary = RequestGenerateDetails::join('products', 'products.id', '=', 'request_generate_details.product_id')
            ->join('parties as supplier', 'supplier.id', '=', 'request_generate_details.supplier_id')
           ->join('parties as purchaser', 'purchaser.id', '=', 'request_generate_details.purchaser_id')
           ->select(
               'request_generate_details.po_date as date',
               'request_generate_details.bill_no',
               'request_generate_details.po as po',
               'supplier.party_name as supplier_name',
               'purchaser.party_name as purchaser_name',
               'products.code',
               'products.product_name',
               'products.uom as uom',
               'request_generate_details.qty as qty',
            //    DB::raw('SUM(request_generate_details.qty) as qty')
           )
           ->whereDate('request_generate_details.po_date', '>=', $fromDate)
           ->whereDate('request_generate_details.po_date', '<=', $toDate)
           ->where('request_generate_details.type', 'PO')
        //    ->where('request_generate_details.type', 'Request Generate')
           // ->where('request_generate_details.product_id', $product_id)
           ->where(function ($queryy) use ($supplier_id) {
               if ($supplier_id != 0) {
                   $queryy->where('request_generate_details.supplier_id', $supplier_id);
               }
           })
           ->where(function ($queryy) use ($purchaser_id) {
               if ($purchaser_id != 0) {
                   $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
               }
           })
           ->where(function ($queryy) use ($product_id) {
               if ($product_id != 0) {
                   $queryy->where('request_generate_details.product_id', $product_id);
               }
           })
        //    ->groupBy('request_generate_details.product_id')
        //    ->groupBy('request_generate_details.bill_no')
           ->orderBy('request_generate_details.bill_no', 'asc')
           ->get();
           $pdf = PDF::loadView('request-generate.po-report.detail', compact('ReqGeneReportSummary', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product'));
           $fileName =  'PO Report Detail.pdf';
           $pdf->save(base_path('upload/request-generate/' . $fileName));
           return $fileName;
        }
    }
}
