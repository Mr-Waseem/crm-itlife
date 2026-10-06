<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Catagory;
use App\Models\Product;
use App\Models\StockDetails;
use App\Models\StockTransfer;
use App\Models\StockTransferDetails;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use App\Models\GodownStockDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables as DataTables;
class StockTransferReportController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }


    public function TransferReport(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'STOCK TRANSFER REPORT')
        ->where('right_name', 'PRINT')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        // return "d";
        if ($request->ajax()) {
            //   return $request;
           $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
            $bill_type = $request->bill_type;
            
            if ($report_type == "summary") {
                //   return "ddds";
                 return $stockReportSummaryWise = Product::join('godown_stock_details', 'godown_stock_details.product_id', '=', 'products.id')
                //  ->join('godown_stocks', 'godown_stocks.id', '=', 'godown_stock_details.transaction_id')
                //  ->join('warehouses', 'warehouses.id', '=', 'godown_stocks.from_warehouse_id')
                //  ->join('warehouses as towarehouse', 'towarehouse.id', '=', 'godown_stocks.to_warehouse_id')
                 ->select(
                        'products.id',
                        'products.code',
                        'products.product_name',
                        'products.packing',
                        // 'warehouses.name as warehouseFrom',
                        // 'towarehouse.name as warehouseTo',

                        // DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                        // DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
                        DB::raw('sum(case when godown_stock_details.date < "'.$fromDate.' as DATE" then godown_stock_details.qty_in end) as openingIn'),
                        DB::raw('sum(case when godown_stock_details.date < "'.$fromDate.' as DATE" then godown_stock_details.qty_out end) as openingOut'),
                        DB::raw('sum(case when godown_stock_details.date >= "'.$fromDate.' as DATE" AND godown_stock_details.date <= "'.$toDate.' as DATE" then godown_stock_details.qty_in end) as qty_in'),
                        DB::raw('sum(case when godown_stock_details.date >= "'.$fromDate.' as DATE" AND godown_stock_details.date <= "'.$toDate.' as DATE" then godown_stock_details.qty_out end) as qty_out'),
                        
                    )
                    ->where('type', 'STOCK TRANSFER')
                    ->Orwhere('type', 'BATCH STOCK TRANSFER')
                    ->orderBy('products.code', 'asc')
                    
                    ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('godown_stock_details.warehouse_id');
                        } else {
                            $query->where('godown_stock_details.warehouse_id', $warehouse_id);
                        }
                    })
                    ->where(function ($query) use ($bill_type) {
                        if ($bill_type != 0) {
                            $query->where('godown_stock_details.type', $bill_type);
                        } 
                    })
                    ->where(function ($query) use ($product_id) {
                        if ($product_id != 0) {
                            $query->where('godown_stock_details.product_id', $product_id);
                        }
                    })
                    
                    ->groupBy('godown_stock_details.product_id')
                    // ->orderBy('godown_stock_details.id')
                    ->get();


                return Response::json($stockReportSummaryWise);
            } 
            else if ($report_type == 'detailed') {
                
                 $stockReportDetailWise = GodownStockDetail::with('product:id,product_name,uom,packing,code')
                 ->with(['godownstock' => function($query){
                    $query->with('warehouse_from');
                    $query->with('warehouse_to');
                 }])
                 ->with('party:id,party_name,code')
                 ->where('type', 'STOCK TRANSFER')
                 ->Orwhere('type', 'BATCH STOCK TRANSFER')
                 ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('warehouse_id')->get();
                        } else {
                            $query->where('warehouse_id', $warehouse_id)->get();
                        }
                        $query->with('warehouse:id,name');
                    })
                    ->where(function ($query) use ($bill_type) {
                        if ($bill_type != 0) {
                            $query->where('godown_stock_details.type', $bill_type);
                        } 
                    })
                    ->where(function ($query) use ($product_id) {
                        if ($product_id != 0) {
                            $query->where('godown_stock_details.product_id', $product_id);
                        }
                    })
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    // ->groupBy('godown_stock_details.product_id')
                    ->orderBy('date')
                    ->orderBy('voucher_no')
                    ->orderBy('id')
                    ->get();

                  $openingStock = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
                    ->select(
                        'products.code',
                        'products.product_name',
                        'products.packing',
                        DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                        DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
                        'godown_stock_details.date'
                    )
                    ->where('type', 'STOCK TRANSFER')
                    ->Orwhere('type', 'BATCH STOCK TRANSFER')
                    ->where(function ($query) use ($warehouse_id) {
                        if ($warehouse_id == 0) {
                            $query->orderBy('godown_stock_details.warehouse_id');
                        } else {
                            $query->where('godown_stock_details.warehouse_id', $warehouse_id);
                        }
                    })
                    ->where(function ($query) use ($product_id) {
                        if ($product_id != 0) {
                            $query->where('godown_stock_details.product_id', $product_id);
                            $query->groupBy('godown_stock_details.product_id');
                        }
                    })
                    ->where(function ($query) use ($bill_type) {
                        if ($bill_type != 0) {
                            $query->where('godown_stock_details.type', $bill_type);
                        } 
                    })
                    // ->where('godown_stock_details.product_id', $product_id)
                    
                    ->whereDate('godown_stock_details.date', '<', $fromDate)
                    // ->groupBy('godown_stock_details.product_id')
                    ->first();

                    return $response = [
                        'stockReportDetailWise' => $stockReportDetailWise,
                        'openingStock' => $openingStock
                    ];
                return Response::json($response);
            }
          
        }

        //   $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('All Product', 0);
           $products = Product::select(DB::raw('CONCAT(`code`, "-", `product_name`) AS `product_name`, `id`'))
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('All Products', '0');

            $products1 = Product::join('godown_stock_details', 'godown_stock_details.product_id', 'products.id')
            // ->select('products.product_name')
            ->select('products.id', 'products.product_name', 'products.code', DB::raw('SUM(godown_stock_details.qty_in) as InQty'), DB::raw('SUM(godown_stock_details.qty_out) as OutQty'))
            ->groupby('godown_stock_details.product_id')
             ->where('godown_stock_details.warehouse_id', Auth::User()->warehouse_id)
             // ->where('product_type','Finish')
             //->OrderBy('id', 'asc')
             //->pluck('product_name', 'id');
             ->get();
            
             $StockProduct = [];
             foreach($products1 as $product){
                $qty = $product->InQty - $product->OutQty;
                if($qty > 0){
                    $StockProduct[] = $product;
                }
             }

            //  return $StockProduct;

            $SingleWarehouseproducts = Product::where('warehouse_id', Auth::User()->warehouse_id)
            ->select(DB::raw('CONCAT(`code`, "-", `product_name`) AS `product_name`, `id`'))
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('All Product', 0);
             $types = GodownStockDetail::select('type')->groupby('type')
            ->where('type', '!=', "OPENING PET ROLL")
            ->where('type', '!=', "OPENING STOCK")
            ->pluck('type', 'type')->prepend('All', '0');
            //  $types = ['0' => 'ALL','PRODUCTION' => 'PRODUCTION', 'PRODUCTION ONE' => 'PRODUCTION ONE', 
            // 'STOCK TRANSFER' => 'STOCK TRANSFER', 'STOCK ISSUE' => 'STOCK ISSUE', 'DC' => 'DC', 
            // 'DCNonGST' => 'DC Non GST', 'GRN' => 'GRN','SALE RETURN' => 'SALE RETURN','PURCHASE RETURN' => 'PURCHASE RETURN'];
        $warehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->orderBy('name')->pluck('name', 'id')->prepend('All', 0);
        //   return $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
    //    return $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->get();
       $SingleWarehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
    //    $SingleWarehouseproducts = Product::where('warehouse_id', Auth::User()->warehouse_id)->pluck('product_name', 'id')->prepend('All Product', 0);
        return view('stock-transfer.report.index', compact('products', 'warehouse', 'SingleWarehouse', 'SingleWarehouseproducts', 'SingleWarehouseproducts', 'StockProduct', 'types'));
    }


    public function PrintStock(Request $request){
        //  return "dd";
                $fromDate = $request->from_date;
                $toDate = $request->to_date;
                $product_id = $request->product_id;
                $warehouse_id = $request->warehouse_id;
                $report_type = $request->report_type;
                $bill_type = $request->bill_type;
                // return "hello";
                // $Warehouse = $request->Warehouse_ID;
                // $Warehouse = 6;
                    // $allproducts = Catagory::with(['products' => function($query) use ($Warehouse){
                    //     $query->with('warehouse:id,name');
                    //     $query->where('warehouse_id', $Warehouse);
                    //     $query->OrderBy('code', 'asc');
                    // }])
                    //     ->orderBy('catagory_code', 'asc')
                    //     ->get();
        
                    if ($report_type == "summary") {
        
        
                    $stockReportSummaryWise = Product::join('godown_stock_details', 'godown_stock_details.product_id', '=', 'products.id')
                            ->select(
                                'products.id',
                                'products.code',
                                'products.product_name',
                                'products.packing',
                                // DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                                // DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
                                DB::raw('sum(case when godown_stock_details.date < "'.$fromDate.' as DATE" then godown_stock_details.qty_in end) as openingIn'),
                                DB::raw('sum(case when godown_stock_details.date < "'.$fromDate.' as DATE" then godown_stock_details.qty_out end) as openingOut'),
                                DB::raw('sum(case when godown_stock_details.date >= "'.$fromDate.' as DATE" AND godown_stock_details.date <= "'.$toDate.' as DATE" then godown_stock_details.qty_in end) as qty_in'),
                                DB::raw('sum(case when godown_stock_details.date >= "'.$fromDate.' as DATE" AND godown_stock_details.date <= "'.$toDate.' as DATE" then godown_stock_details.qty_out end) as qty_out'),
                                
                            )
                            ->where('type', 'STOCK TRANSFER')
                            ->Orwhere('type', 'BATCH STOCK TRANSFER')
                            ->where(function ($query) use ($warehouse_id) {
                                if ($warehouse_id == 0) {
                                    $query->orderBy('godown_stock_details.warehouse_id');
                                } else {
                                    $query->where('godown_stock_details.warehouse_id', $warehouse_id);
                                }
                            })
                            ->where(function ($query) use ($bill_type) {
                                if ($bill_type != 0) {
                                    $query->where('godown_stock_details.type', $bill_type);
                                } 
                            })
                            ->where(function ($query) use ($product_id) {
                                if ($product_id != 0) {
                                    $query->where('godown_stock_details.product_id', $product_id);
                                }
                            })
                            ->orderBy('products.code')
                            ->groupBy('godown_stock_details.product_id')
                            // ->orderBy('godown_stock_details.id')
                            
                            
                            ->get();
                            $warehouse = Warehouse::where('id', $warehouse_id)->first();
                    $pdf = PDF::loadView('stock-transfer.report.print', compact('stockReportSummaryWise', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
                    $fileName =  'Transfer-Report.pdf';
                    $pdf->save(base_path('upload/stock/' . $fileName));
                    return $fileName;
                    }else if ($report_type == 'detailed') {
                        $stockReportDetailWise = GodownStockDetail::with('product:id,product_name,uom,packing,code')
                        ->with(['godownstock' => function($query){
                            $query->with('warehouse_from');
                            $query->with('warehouse_to');
                         }])
                        ->with('party:id,party_name,code')
                        ->where('type', 'STOCK TRANSFER')
                        ->Orwhere('type', 'BATCH STOCK TRANSFER')
                        ->where(function ($query) use ($warehouse_id) {
                                if ($warehouse_id == 0) {
                                    $query->orderBy('warehouse_id')->get();
                                } else {
                                    $query->where('warehouse_id', $warehouse_id)->get();
                                }
                                $query->with('warehouse:id,name');
                            })
                            ->where(function ($query) use ($bill_type) {
                                if ($bill_type != 0) {
                                    $query->where('godown_stock_details.type', $bill_type);
                                } 
                            })
                            ->where(function ($query) use ($product_id) {
                                if ($product_id != 0) {
                                    $query->where('godown_stock_details.product_id', $product_id);
                                }
                            })
                            ->whereDate('date', '>=', $fromDate)
                            ->whereDate('date', '<=', $toDate)
                            // ->groupBy('godown_stock_details.product_id')
                            ->orderBy('date')
                            ->orderBy('voucher_no')
                            ->orderBy('id')
                            ->get();
        
                         $openingStock = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
                            ->select(
                                'products.code',
                                'products.product_name',
                                'products.packing',
                                DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
                                DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
                                'godown_stock_details.date'
                            )
                            ->where('type', 'STOCK TRANSFER')
                            ->Orwhere('type', 'BATCH STOCK TRANSFER')
                            ->where(function ($query) use ($warehouse_id) {
                                if ($warehouse_id == 0) {
                                    $query->orderBy('godown_stock_details.warehouse_id');
                                } else {
                                    $query->where('godown_stock_details.warehouse_id', $warehouse_id);
                                }
                            })
                            ->where(function ($query) use ($bill_type) {
                                if ($bill_type != 0) {
                                    $query->where('godown_stock_details.type', $bill_type);
                                } 
                            })
                            ->where(function ($query) use ($product_id) {
                                if ($product_id != 0) {
                                    $query->where('godown_stock_details.product_id', $product_id);
                                    $query->groupBy('godown_stock_details.product_id');
                                }
                            })
                            // ->where('godown_stock_details.product_id', $product_id)
                            
                            ->whereDate('godown_stock_details.date', '<', $fromDate)
                            // ->groupBy('godown_stock_details.product_id')
                            ->first();
                            $warehouse = Warehouse::where('id', $warehouse_id)->first();
        
                            $pdf = PDF::loadView('stock-transfer.report.detail-print', compact('stockReportDetailWise', 'openingStock', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
                    $fileName =  'Transfer-Report.pdf';
                    $pdf->save(base_path('upload/stock/' . $fileName));
                    return $fileName;
        
                        //     return $response = [
                        //         'stockReportDetailWise' => $stockReportDetailWise,
                        //         'openingStock' => $openingStock
                        //     ];
                        // return Response::json($response);
                    }
        
            }
}
