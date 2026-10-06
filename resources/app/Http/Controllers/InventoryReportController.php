<?php

namespace App\Http\Controllers;
use PDF;
use App\Models\Catagory;
use App\Models\Product;
use App\Models\StockDetails;
use App\Models\StockTransfer;
use App\Models\StockTransferDetails;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use App\Models\GodownStockDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables as DataTables;

class InventoryReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function StockReport(Request $request)
    {
        if ($request->ajax()) {
            //  return "0";
           $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $productGroupid = $request->productGroupid;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
            
            if ($report_type == "summary") {
                //  return "ddds";
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
                    ->orderBy('products.code', 'asc')
                    
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
                        }
                    })
                    ->where(function ($query) use ($productGroupid) {
                        if ($productGroupid != 0) {
                            $query->where('products.category_id', $productGroupid);
                        }
                    })
                    
                    ->groupBy('godown_stock_details.product_id')
                    ->orderBy('products.code')
                    ->get();


                return Response::json($stockReportSummaryWise);
            } 
            else if ($report_type == 'detailed') {
                
                //  $stockReportDetailWise = GodownStockDetail::with('product:id,product_name,uom,packing,code')
                 $stockReportDetailWise = GodownStockDetail::with(['product' => function($query1) use($productGroupid){
                    // $query1->where(function ($query) use ($productGroupid) {
                        if ($productGroupid != 0) {
                            $query1->where('products.category_id', $productGroupid);
                        }
                    // })
                 }])
                 ->with(['godownstock' => function($query){
                    $query->with('warehouse_from');
                    $query->with('warehouse_to');
                 }])
                 
                 ->with('party:id,party_name,code')
                 //dc data not goes to godownstock. so we need warehouse
                 ->with(['delivery_challan' => function($query){
                    $query->with('warehouse');
                 }])
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
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    // ->groupBy('godown_stock_details.product_id')
                    // ->orderBy('date')
                    ->orderBy('date')
                    ->orderBy('voucher_no')
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

        //  return "dss";
        //   $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('All Product', 0);
         $products = Product::select(DB::raw('CONCAT(`code`, "-", `product_name`) AS `product_name`, `id`'))
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('Select Product', '0');

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
                
                    $StockProduct[] = $product;
                
                // if($qty > 0){
                //     $StockProduct[] = $product;
                // }
             }
            //  return $StockProduct;
             $productGroup = Catagory::Orderby('catagory_name', 'asc')->pluck('catagory_name', 'id')->prepend('All Category', 0);
            $SingleWarehouseproducts = Product::where('warehouse_id', Auth::User()->warehouse_id)
            ->select(DB::raw('CONCAT(`code`, "-", `product_name`) AS `product_name`, `id`'))
            ->OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('All Product', 0);

        $warehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->orderBy('name')->pluck('name', 'id')->prepend('All', 0);
        //   return $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
    //    return $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id')->get();
       $SingleWarehouse = Warehouse::select(DB::raw('CONCAT(`id`,"_",`name`) as id,name'))->where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
    //    $SingleWarehouseproducts = Product::where('warehouse_id', Auth::User()->warehouse_id)->pluck('product_name', 'id')->prepend('All Product', 0);
        return view('inventory-reports.index', compact('products', 'warehouse', 'SingleWarehouse', 'SingleWarehouseproducts', 'SingleWarehouseproducts', 'StockProduct', 'productGroup'));
    }

    public function InventoryReport(Request $request)
    {
        if ($request->ajax()) {
            if ($request->warehouse_id == null) {
                $sum = 0;
                $stockDetails = StockDetails::with('products:id,product_name')->where('product_id', $request->product_id)->get();
                return DataTables::of($stockDetails)
                    ->addIndexColumn()
                    ->addColumn('date', function ($data) {
                        return date('d/m/Y', strtotime($data->date));
                    })
                    ->addColumn('type', function ($data) {
                        if ($data->type == 'Sale') {
                            return 'SA';
                        } else
                    if ($data->type == 'Sales Return') {
                            return 'SR';
                        } else
                    if ($data->type == 'Purchase') {
                            return 'PU';
                        } else
                    if ($data->type == 'Purchase Return') {
                            return 'PR';
                        }
                    })
                    ->addColumn('description', function ($data) {
                        return $data->products->product_name;
                    })
                    ->addColumn('qty_in', function ($data) use ($sum) {
                        $sum = $sum + $data->qty_in;
                        return number_format($data->qty_in, 2);
                    })
                    ->addColumn('qty_out', function ($data) {
                        return number_format($data->qty_out, 2);
                    })
                    ->addColumn('bal_qty', function ($data) use ($sum) {
                        // return $data->qty_in - $data->qty_out;
                        // $sum = 0;
                        $sum++;
                        return $sum;
                    })
                    ->make(true);
            } else {
                $sum = 0;
                $stockDetails = StockDetails::with('products:id,product_name')
                    ->where('product_id', $request->product_id)
                    ->where('warehouse_id', $request->warehouse_id)
                    ->get();
                return DataTables::of($stockDetails)
                    ->addIndexColumn()
                    ->addColumn('date', function ($data) {
                        return date('d/m/Y', strtotime($data->date));
                    })
                    ->addColumn('type', function ($data) {
                        if ($data->type == 'Sale') {
                            return 'SA';
                        } else
                    if ($data->type == 'Sales Return') {
                            return 'SR';
                        } else
                    if ($data->type == 'Purchase') {
                            return 'PU';
                        } else
                    if ($data->type == 'Purchase Return') {
                            return 'PR';
                        }
                    })
                    ->addColumn('description', function ($data) {
                        return $data->products->product_name;
                    })
                    ->addColumn('qty_in', function ($data) use ($sum) {
                        $sum = $sum + $data->qty_in;
                        return number_format($data->qty_in, 2);
                    })
                    ->addColumn('qty_out', function ($data) {
                        return number_format($data->qty_out, 2);
                    })
                    ->addColumn('bal_qty', function ($data) use ($sum) {
                        // return $data->qty_in - $data->qty_out;
                        // $sum = 0;
                        $sum++;
                        return $sum;
                    })
                    ->make(true);
            }
        }

        $products = Product::orderBy('product_name')->pluck('product_name', 'id')->prepend('Select Product', '');
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('All', '');
        $SingleWarehouse = Warehouse::where('warehouse_id', Auth::User()->warehouse_id)->pluck('name', 'id')->first();
        return view('inventory-reports.product-ledger', compact('products', 'warehouse', 'SingleWarehouse'));
    }

    public function StockReports(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'STOCK TRANSFER REPORT')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $warehouse_id = $request->warehouse_id;
            $productGroupid = $request->productGroupid;
            $warehouse = Warehouse::find($warehouse_id);

            if ($product_id == 0) {
                $stockReportSummary = StockTransferDetails::join('products', 'products.id', '=', 'stock_transfer_details.product_id')
                    ->join('warehouses', 'warehouses.id', '=', 'stock_transfer_details.warehouse_id')
                    ->select(
                        'stock_transfer_details.date as date',
                        'stock_transfer_details.bill_no',
                        'products.product_name',
                        'products.uom as uom',
                        'warehouses.name as warehouse_name',
                        'stock_transfer_details.sale_amount',
                        DB::raw('SUM(stock_transfer_details.qty_in) as qty_in'),
                        DB::raw('SUM(stock_transfer_details.qty_out) as qty_out')
                    )
                    ->whereDate('stock_transfer_details.date', '>=', $fromDate)
                    ->whereDate('stock_transfer_details.date', '<=', $toDate)
                    ->where('stock_transfer_details.warehouse_id', $warehouse_id)
                    // ->where('stock_transfer_details.product_id', $product_id)
                    // ->groupBy('stock_transfer_details.warehouse_id')
                    ->groupBy('stock_transfer_details.product_id')
                    ->orderBy('stock_transfer_details.date', 'asc')
                    ->get();

                return Response::json(['data' => $stockReportSummary, 'warehouse' => $warehouse]);
            } else {

                $stockReportDetailWise = StockTransferDetails::with('product:id,product_name,uom')
                    ->with('warehouse:id,name')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('warehouse_id', $warehouse_id)
                    ->where('product_id', $product_id)
                    ->orderBy('date', 'asc')
                    ->get();

                return Response::json(['data' => $stockReportDetailWise, 'warehouse' => $warehouse]);
            }
        }
        // return "ddd";


        $products = Product::where('warehouse_id', Auth::User()->warehouse_id)->pluck('product_name', 'id')->prepend('All', 0);
        $warehouses = Warehouse::pluck('name', 'id')->prepend('Select Godown', '');
        return view('inventory-reports.stock-transfer', compact('products', 'warehouses'));
    }


    public function LoadProducts(Request $request){
        // return $request;
        $products1 = Product::join('godown_stock_details', 'godown_stock_details.product_id', 'products.id')
            // ->select('products.product_name')
            ->select('products.id', 'products.product_name', 'products.code', DB::raw('SUM(godown_stock_details.qty_in) as InQty'), DB::raw('SUM(godown_stock_details.qty_out) as OutQty'))
            ->groupby('godown_stock_details.product_id')
             ->where('godown_stock_details.warehouse_id', $request->warehouseID)
             // ->where('product_type','Finish')
             ->OrderBy('products.code', 'asc')
             //->pluck('product_name', 'id');
             ->get();
            
             $StockProduct = [];
             foreach($products1 as $product){
                $qty = $product->InQty - $product->OutQty;
                
                    $StockProduct[] = $product;
                
                // if($qty > 0){
                //     $StockProduct[] = $product;
                // }
             }
             return Response::json(['data' => $StockProduct]);
    }


    public function PrintStock(Request $request){
    //  return "dd";
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $product_id = $request->product_id;
        $productGroupid = $request->productGroupid;
        $warehouse_id = $request->warehouse_id;
        $report_type = $request->report_type;
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
                        }
                    })
                    ->where(function ($query) use ($productGroupid) {
                        if ($productGroupid != 0) {
                            $query->where('products.category_id', $productGroupid);
                        }
                    })
                    ->orderBy('products.code')
                    ->groupBy('godown_stock_details.product_id')
                    // ->orderBy('godown_stock_details.id')
                    
                    
                    ->get();
                    $warehouse = Warehouse::where('id', $warehouse_id)->first();
            $pdf = PDF::loadView('inventory-reports.print', compact('stockReportSummaryWise', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
            $fileName =  'Stock-Report.pdf';
            $pdf->save(base_path('upload/stock/' . $fileName));
            return $fileName;
            }else if ($report_type == 'detailed') {
                $stockReportDetailWise = GodownStockDetail::with(['product' => function($query1) use($productGroupid){
                    // $query1->where(function ($query) use ($productGroupid) {
                        if ($productGroupid != 0) {
                            $query1->where('products.category_id', $productGroupid);
                        }
                    // });
                 }])
                ->with(['godownstock' => function($query){
                    $query->with('warehouse_from');
                    $query->with('warehouse_to');
                 }])
                ->with('party:id,party_name,code')
                ->with(['delivery_challan' => function($query){
                    $query->with('warehouse');
                 }])
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
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    // ->groupBy('godown_stock_details.product_id')
                    ->orderBy('date')
                    ->orderBy('voucher_no')
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
                    // ->where('godown_stock_details.product_id', $product_id)
                    
                    ->whereDate('godown_stock_details.date', '<', $fromDate)
                    // ->groupBy('godown_stock_details.product_id')
                    ->first();
                    $warehouse = Warehouse::where('id', $warehouse_id)->first();
                    $pdf = PDF::loadView('inventory-reports.detail-print', compact('stockReportDetailWise', 'openingStock', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
            $fileName =  'Stock-Report.pdf';
            $pdf->save(base_path('upload/stock/' . $fileName));
            return $fileName;

                //     return $response = [
                //         'stockReportDetailWise' => $stockReportDetailWise,
                //         'openingStock' => $openingStock
                //     ];
                // return Response::json($response);
            }

    }


    public function PrintStock1(Request $request){
        //  return "dd";
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $product_id = $request->product_id;
            $productGroupid = $request->productGroupid;
            $warehouse_id = $request->warehouse_id;
            $report_type = $request->report_type;
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
                            }
                        })
                        ->where(function ($query) use ($productGroupid) {
                            if ($productGroupid != 0) {
                                $query->where('products.category_id', $productGroupid);
                            }
                        })
                        ->orderBy('products.code')
                        ->groupBy('godown_stock_details.product_id')
                        // ->orderBy('godown_stock_details.id')
                        
                        
                        ->get();
                        $warehouse = Warehouse::where('id', $warehouse_id)->first();
                $pdf = PDF::loadView('inventory-reports.print1', compact('stockReportSummaryWise', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
                $fileName =  'Stock-Report.pdf';
                $pdf->save(base_path('upload/stock/' . $fileName));
                return $fileName;
                }else if ($report_type == 'detailed') {
                    $stockReportDetailWise = GodownStockDetail::with(['product' => function($query1) use($productGroupid){
                        // $query1->where(function ($query) use ($productGroupid) {
                            if ($productGroupid != 0) {
                                $query1->where('products.category_id', $productGroupid);
                            }
                        // });
                     }])
                    ->with(['godownstock' => function($query){
                        $query->with('warehouse_from');
                        $query->with('warehouse_to');
                     }])
                    ->with('party:id,party_name,code')
                    ->with(['delivery_challan' => function($query){
                        $query->with('warehouse');
                     }])
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
                        ->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        // ->groupBy('godown_stock_details.product_id')
                        ->orderBy('date')
                        ->orderBy('voucher_no')
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
                        // ->where('godown_stock_details.product_id', $product_id)
                        ->whereDate('godown_stock_details.date', '<', $fromDate)
                        // ->groupBy('godown_stock_details.product_id')
                        ->first();
                        $warehouse = Warehouse::where('id', $warehouse_id)->first();
                        $pdf = PDF::loadView('inventory-reports.detail-print1', compact('stockReportDetailWise', 'openingStock', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
                $fileName =  'Stock-Report.pdf';
                $pdf->save(base_path('upload/stock/' . $fileName));
                return $fileName;
    
                    //     return $response = [
                    //         'stockReportDetailWise' => $stockReportDetailWise,
                    //         'openingStock' => $openingStock
                    //     ];
                    // return Response::json($response);
                }
    
        }


    // public function PrintSingleProduct(Request $request){
        
    //         $fromDate = $request->from_date;
    //         $toDate = $request->to_date;
    //         $product_id = $request->product_id;
    //         $warehouse_id = $request->warehouse_id;
    //         $report_type = $request->report_type;

    //                 $stockReportDetailWise = GodownStockDetail::with('product:id,product_name,uom,packing,code')
    //                 ->with(['godownstock' => function($query){
    //                     $query->with('warehouse_from');
    //                     $query->with('warehouse_to');
    //                  }])
    //                 ->with('party:id,party_name,code')
    //                 ->with(['delivery_challan' => function($query){
    //                     $query->with('warehouse');
    //                  }])
    //                     ->where(function ($query) use ($warehouse_id) {
    //                         if ($warehouse_id == 0) {
    //                             $query->orderBy('warehouse_id')->get();
    //                         } else {
    //                             $query->where('warehouse_id', $warehouse_id)->get();
    //                         }
    //                         $query->with('warehouse:id,name');
    //                     })
    //                     ->where(function ($query) use ($product_id) {
    //                         if ($product_id != 0) {
    //                             $query->where('godown_stock_details.product_id', $product_id);
    //                         }
    //                     })
    //                     ->whereDate('date', '>=', $fromDate)
    //                     ->whereDate('date', '<=', $toDate)
    //                     // ->groupBy('godown_stock_details.product_id')
    //                     ->orderBy('date')
    //                     ->orderBy('voucher_no')
    //                     ->get();
    
    //                  $openingStock = GodownStockDetail::join('products', 'products.id', '=', 'godown_stock_details.product_id')
    //                     ->select(
    //                         'products.code',
    //                         'products.product_name',
    //                         'products.packing',
    //                         DB::raw('SUM(godown_stock_details.qty_in) as qty_in'),
    //                         DB::raw('SUM(godown_stock_details.qty_out) as qty_out'),
    //                         'godown_stock_details.date'
    //                     )
    //                     ->where(function ($query) use ($warehouse_id) {
    //                         if ($warehouse_id == 0) {
    //                             $query->orderBy('godown_stock_details.warehouse_id');
    //                         } else {
    //                             $query->where('godown_stock_details.warehouse_id', $warehouse_id);
    //                         }
    //                     })
    //                     ->where(function ($query) use ($product_id) {
    //                         if ($product_id != 0) {
    //                             $query->where('godown_stock_details.product_id', $product_id);
    //                             $query->groupBy('godown_stock_details.product_id');
    //                         }
    //                     })
    //                     // ->where('godown_stock_details.product_id', $product_id)
                        
    //                     ->whereDate('godown_stock_details.date', '<', $fromDate)
    //                     // ->groupBy('godown_stock_details.product_id')
    //                     ->first();
    //                     $warehouse = Warehouse::where('id', $warehouse_id)->first();
    //                     $pdf = PDF::loadView('inventory-reports.detail-print', compact('stockReportDetailWise', 'openingStock', 'fromDate', 'toDate', 'warehouse'))->setPaper('a4', 'landscape');
    //             $fileName =  'Stock-Report.pdf';
    //             $pdf->save(base_path('upload/stock/' . $fileName));
    //             return $fileName;
    
    //     }
    
}