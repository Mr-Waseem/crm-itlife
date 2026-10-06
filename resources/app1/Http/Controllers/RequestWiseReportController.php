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

class RequestWiseReportController extends Controller
{
    public function index(Request $request)
    {
        $usertype = Auth::User()->role;
        if( $usertype == "Admin"){
            $warehouse = Warehouse::pluck('name', 'id')->prepend('All Warehouses', '0');
        }else{
            $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        }
         
        //  return $warehouse;
            
            $products = Product::pluck('product_name', 'id')->prepend('All Products', 0);
            $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('All Suppliers', 0);
            $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('All Purchasers', 0);
            return view('request-generate.request-wise-report.index', compact('products', 'supplier', 'purchaser', 'warehouse'));
        
    }

    public function report(Request $request)
    {
       

        // return $request;
        // if ($request->ajax()) {

            $WarehouseID = $request->WarehouseID;
            $requestFrom = $request->requestFrom;
            $requestTo = $request->requestTo;
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
                            'request_generate_details.date as date',
                            'request_generate_details.bill_no',
                            'supplier.party_name as supplier_name',
                            'purchaser.party_name as purchaser_name',
                            'products.code',
                            'products.product_name',
                            'products.uom as uom',
                            DB::raw('SUM(request_generate_details.qty) as qty')
                        )
                        // ->whereDate('request_generate_details.date', '>=', $fromDate)
                        // ->whereDate('request_generate_details.date', '<=', $toDate)
                        ->where('request_generate_details.type', 'Request Generate')
                        // ->where('request_generate_details.warehouse_id', Auth::User()->warehouse_id)
                        ->where('request_generate_details.bill_no', '>=', $requestFrom)
                        ->where('request_generate_details.bill_no', '<=', $requestTo)
                        
                        // ->where('request_generate_details.product_id', $product_id)
                        ->where(function ($queryy) use ($WarehouseID) {
                            if ($WarehouseID != 0) {
                                $queryy->where('request_generate_details.warehouse_id', $WarehouseID);
                            }
                        })
                        // ->where(function ($queryy) use ($purchaser_id) {
                        //     if ($purchaser_id != 0) {
                        //         $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
                        //     }
                        // })
                        // ->where(function ($queryy) use ($product_id) {
                        //     if ($product_id != 0) {
                        //         $queryy->where('request_generate_details.product_id', $product_id);
                        //     }
                        // })
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
                   'request_generate_details.date as date',
                   'request_generate_details.bill_no',
                   'supplier.party_name as supplier_name',
                   'purchaser.party_name as purchaser_name',
                   'products.code',
                   'products.product_name',
                   'products.uom as uom',
                   'request_generate_details.qty as qty',
                //    DB::raw('SUM(request_generate_details.qty) as qty')
               )
               ->where('request_generate_details.type', 'Request Generate')
            //    ->where('request_generate_details.warehouse_id', Auth::User()->warehouse_id)
                    ->where('request_generate_details.bill_no', '>=', $requestFrom)
                    ->where('request_generate_details.bill_no', '<=', $requestTo)
                    ->where(function ($queryy) use ($WarehouseID) {
                        if ($WarehouseID != 0) {
                            $queryy->where('request_generate_details.warehouse_id', $WarehouseID);
                        }
                    })
               // ->where('request_generate_details.product_id', $product_id)
            //    ->where(function ($queryy) use ($supplier_id) {
            //        if ($supplier_id != 0) {
            //            $queryy->where('request_generate_details.supplier_id', $supplier_id);
            //        }
            //    })
            //    ->where(function ($queryy) use ($purchaser_id) {
            //        if ($purchaser_id != 0) {
            //            $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
            //        }
            //    })
            //    ->where(function ($queryy) use ($product_id) {
            //        if ($product_id != 0) {
            //            $queryy->where('request_generate_details.product_id', $product_id);
            //        }
            //    })
            //    ->groupBy('request_generate_details.product_id')
            //    ->groupBy('request_generate_details.bill_no')
               ->orderBy('request_generate_details.bill_no', 'asc')
               ->get();
           return Response::json(['data' => $ReqGeneReportSummary]);
            }
    }

    public function printPDF(Request $request){
        $requestFrom = $request->requestFrom;
        $requestTo = $request->requestTo;
        $reportType = $request->report_type;
        $WarehouseID = $request->WarehouseID;
       
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
                        'request_generate_details.date as date',
                        'request_generate_details.bill_no',
                        'supplier.party_name as supplier_name',
                        'purchaser.party_name as purchaser_name',
                        'products.code',
                        'products.product_name',
                        'products.uom as uom',
                        DB::raw('SUM(request_generate_details.qty) as qty')
                    )
                    ->where('request_generate_details.type', 'Request Generate')
                    // ->where('request_generate_details.warehouse_id', Auth::User()->warehouse_id)
                    ->where('request_generate_details.bill_no', '>=', $requestFrom)
                    ->where('request_generate_details.bill_no', '<=', $requestTo)
                    ->where(function ($queryy) use ($WarehouseID) {
                        if ($WarehouseID != 0) {
                            $queryy->where('request_generate_details.warehouse_id', $WarehouseID);
                        }
                    })
                    // ->where('request_generate_details.product_id', $product_id)
                    // ->where(function ($queryy) use ($supplier_id) {
                    //     if ($supplier_id != 0) {
                    //         $queryy->where('request_generate_details.supplier_id', $supplier_id);
                    //     }
                    // })
                    // ->where(function ($queryy) use ($purchaser_id) {
                    //     if ($purchaser_id != 0) {
                    //         $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
                    //     }
                    // })
                    // ->where(function ($queryy) use ($product_id) {
                    //     if ($product_id != 0) {
                    //         $queryy->where('request_generate_details.product_id', $product_id);
                    //     }
                    // })
                    // ->groupBy('request_generate_details.product_id')
                    ->groupBy('request_generate_details.bill_no')
                    ->orderBy('request_generate_details.bill_no', 'asc')
                    ->get();

                $pdf = PDF::loadView('request-generate.request-wise-report.summary', compact('ReqGeneReportSummary', 'requestFrom', 'requestTo'));
                $fileName =  'Request Report Summary.pdf';
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
               'request_generate_details.date as date',
               'request_generate_details.bill_no',
               'supplier.party_name as supplier_name',
               'purchaser.party_name as purchaser_name',
               'products.code',
               'products.product_name',
               'products.uom as uom',
               'request_generate_details.qty as qty',
            //    DB::raw('SUM(request_generate_details.qty) as qty')
           )
           ->where('request_generate_details.type', 'Request Generate')
            // ->where('request_generate_details.warehouse_id', Auth::User()->warehouse_id)
           ->where('request_generate_details.bill_no', '>=', $requestFrom)
            ->where('request_generate_details.bill_no', '<=', $requestTo)
            ->where(function ($queryy) use ($WarehouseID) {
                if ($WarehouseID != 0) {
                    $queryy->where('request_generate_details.warehouse_id', $WarehouseID);
                }
            })
        //    ->where('request_generate_details.type', 'Request Generate')
           // ->where('request_generate_details.product_id', $product_id)
        //    ->where(function ($queryy) use ($supplier_id) {
        //        if ($supplier_id != 0) {
        //            $queryy->where('request_generate_details.supplier_id', $supplier_id);
        //        }
        //    })
        //    ->where(function ($queryy) use ($purchaser_id) {
        //        if ($purchaser_id != 0) {
        //            $queryy->where('request_generate_details.purchaser_id', $purchaser_id);
        //        }
        //    })
        //    ->where(function ($queryy) use ($product_id) {
        //        if ($product_id != 0) {
        //            $queryy->where('request_generate_details.product_id', $product_id);
        //        }
        //    })
        //    ->groupBy('request_generate_details.product_id')
        //    ->groupBy('request_generate_details.bill_no')
           ->orderBy('request_generate_details.bill_no', 'asc')
           ->get();
           $pdf = PDF::loadView('request-generate.request-wise-report.detail', compact('ReqGeneReportSummary', 'requestFrom', 'requestTo'));
           $fileName =  'Request Report Detail.pdf';
           $pdf->save(base_path('upload/request-generate/' . $fileName));
           return $fileName;
        }
    }
}
