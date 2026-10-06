<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\UOM;
use App\Models\Product;
use App\Models\Production;
use App\Models\GodownStockDetail;
use App\Models\ProductionDetails;
use App\Models\RecipeCreation;
use App\Models\RecipeCreationDetails;
use App\Models\Machine;
use App\Models\Shift;
use App\Models\Party;
use App\Models\BatchStock;
use App\Models\Color;
use App\Models\VoucherRights;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ProductionReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request){
         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PRODUCTION REPORT')
        ->where('right_name', 'ADD')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        // return "dd";
        // $fromDate = $request->from_date;
        //     $toDate = $request->to_date;
        //     $product_id = $request->product_id;
        //     $warehouse_id = $request->warehouse_id;
        //     $report_type = $request->report_type;
       
        if ($request->ajax()) {
             
           $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
            
            if ($report_type == "summary") {
                //   return "ddds";
                  $data = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
                //   with('product')
                //   ->with(['production' => function($query){
                    //   $query->with('recipe');
                    //   $query->with('machine');
                    //   $query->with('shift');
                    //   $query->with('forman');
                    //   $query->with('operator');
                    //   $query->with('generated_by');
                //   }])
                  ->select(
                    'products.code',
                    'products.product_name',
                    DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                )
                  // ->with('recipe')
                  ->where(function ($query) use ($warehouse_id) {
                      if ($warehouse_id == 0) {
                          $query->orderBy('warehouse_id')->get();
                      } else {
                          $query->where('warehouse_id', $warehouse_id)->get();
                      }
                      $query->with('warehouse:id,name');
                  })
                  ->where(function ($query) use ($product_id) {
                      if ($product_id != 0) {
                          $query->where('godown_stock_details.product_id', $product_id);
                      }
                  })
                  ->where('type', 'PRODUCTION')
                  ->where('qty_in', '>', 0)
                  ->whereDate('date', '>=', $fromDate)
                  ->whereDate('date', '<=', $toDate)
                  ->groupBy('product_id')
                  ->orderBy('date')
                  ->get();
  
  
                      return $response = [
                          'production' => $data
                      ];
                  return Response::json($response);
            } 
            else if ($report_type == 'detailed') {
                //  return "03";
                  $data = GodownStockDetail::with('product:id,product_name,uom,packing,code')
                ->with(['production' => function($query){
                    $query->with('color');
                    $query->with('recipe');
                    $query->with('machine');
                    $query->with('shift');
                    $query->with('forman');
                    $query->with('operator');
                    $query->with('generated_by');
                }])
                // ->with('recipe')
                ->where(function ($query) use ($warehouse_id) {
                    if ($warehouse_id == 0) {
                        $query->orderBy('warehouse_id')->get();
                    } else {
                        $query->where('warehouse_id', $warehouse_id)->get();
                    }
                    // $query->with('warehouse:id,name');
                })
                ->where(function ($query) use ($product_id) {
                    if ($product_id != 0) {
                        $query->where('godown_stock_details.product_id', $product_id);
                    }
                })
                
                ->where('qty_in', '>', 0)
                // ->where('type', 'PRODUCTION')
                // ->orwhere('type', 'PRODUCTION ONE')
                ->where(function ($query) {
                    $query->where('type', 'PRODUCTION')
                          ->orWhere('type', 'PRODUCTION ONE');
                          })
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->orderBy('date')
                ->get();


                    return $response = [
                        'production' => $data
                    ];
                return Response::json($response);
            }
          
        }
        //   $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('Select Product', '0');
         $products = Product::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
        // ->where('product_type', 'Finish')
        // ->where('warehouse_id', Auth::User()->warehouse_id)
        ->OrderBy('code', 'asc')
        ->pluck('product_name', 'id')
        ->prepend('All Products', '0');
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('All', '0');
        $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->first();
        return view('production.report.index', compact('products', 'warehouse', 'SingleWarehouse'));
    }

    public function printPDF(Request $request){
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PRODUCTION REPORT')
        ->where('right_name', 'PRINT')
        ->first();
    if (!$voucherRight) {
        return redirect()->back()->with('access_granted', 'Insufficient Permission.');
    }
        $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
    File::cleanDirectory(base_path() . '/upload/production');

         $data = GodownStockDetail::with('product:id,product_name,uom,packing,code')
        ->with(['production' => function($query){
            $query->with('color');
            $query->with('recipe');
            $query->with('machine');
            $query->with('shift');
            $query->with('forman');
            $query->with('operator');
            $query->with('generated_by');
        }])
        // ->with('recipe')
        ->where(function ($query) use ($warehouse_id) {
            if ($warehouse_id == 0) {
                $query->orderBy('warehouse_id')->get();
            } else {
                $query->where('warehouse_id', $warehouse_id)->get();
            }
            // $query->with('warehouse:id,name');
        })
        ->where(function ($query) use ($product_id) {
            if ($product_id != 0) {
                $query->where('godown_stock_details.product_id', $product_id);
            }
        })
        
        ->where('qty_in', '>', 0)
        // ->where('type', 'PRODUCTION')
        // ->orwhere('type', 'PRODUCTION ONE')
        ->where(function ($query) {
            $query->where('type', 'PRODUCTION')
                  ->orWhere('type', 'PRODUCTION ONE');
                  })
        ->whereDate('date', '>=', $fromDate)
        ->whereDate('date', '<=', $toDate)
        ->orderBy('date')
        ->get();

        $pdf = PDF::loadView('production.report.invoice', compact('data', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        $fileName =  'production-report.pdf';
        $pdf->save(base_path('upload/production/' . $fileName));
        return $fileName;

    }

}
