<?php

namespace App\Http\Controllers;

use App\Models\GRN;
use App\Models\SalePurchase;
use App\Models\Product;
use App\Models\Party;
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

class GRNReportController extends Controller
{

    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $warehouse=Warehouse::pluck('name','id')->prepend('Select Godown','');
        // $supplier = Party::OrderBy('party_name', 'asc')
        //     // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
        //     ->where('account_type', 'SUPPLIER')
        //     ->pluck('party_name', 'id')
        //     ->prepend('All Suppliers', '0');
            $supplier = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('product_type', 'Finish')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('account_type', 'SUPPLIER')
            ->OrderBy('code', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('All Suppliers', 0);
        // return view('purchases.report.index', compact('parties'));

        // $products = Product::pluck('product_name', 'id')->prepend('All Products', 0);
        $products = Product::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`product_name`) `product_name`'))
            // ->where('product_type', 'Finish')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->OrderBy('code', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('All Products', 0);
            // $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('All Suppliers', 0);
        // $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('All Purchasers', 0);
        $purchaser = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->where('product_type', 'Finish')
            // ->where('warehouse_id', Auth::User()->warehouse_id)
            ->where('account_type', 'Purchaser')
            ->OrderBy('code', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('All Purchasers', 0);
        return view('grn.report.index',compact('warehouse', 'supplier', 'products', 'purchaser'));
    }

    public function report(Request $request){
        // if($request->ajax()){
            //   return $request;
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $warehouse_id=$request->warehouse_id;
            $product_id = $request->productID;
            // return "d";
            // $supplier_id = $request->supplier_id;
            $purchaser_id = $request->purchaserID;
            $supplier_id = $request->supplierID;
            $reportType=$request->report_type;
            // return $request;
                if($reportType=='summary'){
                    // $summaryReport=Grn::with('warehouse:id,name')
                    // ->where('warehouse_id',$warehouse_id)
                    // ->whereDate('voucher_date', '>=', $fromDate)
                    // ->whereDate('voucher_date', '<=', $toDate)
                    // ->get();
                    // return "dner";

                    $summaryReport=GodownStock::join('godown_stock_details', 'godown_stock_details.transaction_id', '=', 'godown_stocks.id')
                    ->join('warehouses', 'warehouses.id', '=', 'godown_stocks.to_warehouse_id')
                    ->join('inward_gate_passes', 'inward_gate_passes.id', '=', 'godown_stocks.inward_gatepass_id')
                    ->join('parties', 'parties.id', '=', 'inward_gate_passes.supplier_id')
                    ->join('parties as purchasers', 'purchasers.id', '=', 'inward_gate_passes.purchaser_id')
                    // ->with('warehouse:id,name')
                    ->select(
                        'godown_stocks.date as date',
                        'godown_stocks.voucher_no',
                        'warehouses.name as warehouse_name',
                        'parties.party_name as supplier',
                        'purchasers.party_name as purchaser',
                        DB::raw("SUM(godown_stock_details.qty_in) as total_qty"),
                        DB::raw("SUM(godown_stock_details.demand_qty) as demand_qty"),
                        // DB::raw('sum(case when godown_stock_details.demand_qty < "'.$fromDate.' as DATE" then godown_stock_details.qty_out end) as openingOut'),
                        // DB::raw("SUM(godown_stock_details.qty_in < $fromDate) as total_qty"),
                    )
                    ->where('godown_stocks.type', 'GRN')
                    ->where('godown_stock_details.type', 'GRN')
                    ->where(function ($queryy) use ($supplier_id) {
                        if ($supplier_id != 0) {
                            $queryy->where('parties.id', $supplier_id);
                        }
                    })
                    ->where(function ($queryy) use ($purchaser_id) {
                        if ($purchaser_id != 0) {
                            $queryy->where('purchasers.id', $purchaser_id);
                        }
                    })
                    ->where(function ($queryy) use ($product_id) {
                        if ($product_id != 0) {
                            $queryy->where('godown_stock_details.product_id', $product_id);
                        }
                    })
                    ->groupBy('godown_stocks.voucher_no')
                    ->groupBy('godown_stock_details.voucher_no')
                    // ->where('to_warehouse_id',$warehouse_id)
                    
                    ->whereDate('godown_stocks.date', '>=', $fromDate)
                    ->whereDate('godown_stocks.date', '<=', $toDate)
                    ->get();
                     return response()->json(['data'=> $summaryReport]);
                
            }else if($reportType=='detail'){
                // return "d";
                $detailReport=GodownStock::join('godown_stock_details', 'godown_stock_details.transaction_id', '=', 'godown_stocks.id')
                ->join('warehouses', 'warehouses.id', '=', 'godown_stocks.to_warehouse_id')
                ->join('products', 'products.id', '=', 'godown_stock_details.product_id')
                ->join('inward_gate_passes', 'inward_gate_passes.id', '=', 'godown_stocks.inward_gatepass_id')
                ->join('parties', 'parties.id', '=', 'inward_gate_passes.supplier_id')
                ->join('parties as purchasers', 'purchasers.id', '=', 'inward_gate_passes.purchaser_id')
                // ->with('warehouse:id,name')
                ->select(
                    'godown_stocks.date as date',
                    'godown_stocks.voucher_no',
                    'products.code',
                    'products.product_name',
                    'products.uom',
                    'warehouses.name as warehouse_name',
                    'parties.party_name as supplier',
                    'purchasers.party_name as purchaser',
                    'godown_stock_details.qty_in as qty_in',
                    'godown_stock_details.demand_qty as demand_qty',
                    // DB::raw("SUM(godown_stock_details.qty_in) as total_qty"),
                    // DB::raw("SUM(godown_stock_details.demand_qty) as demand_qty"),
                    // DB::raw('sum(case when godown_stock_details.demand_qty < "'.$fromDate.' as DATE" then godown_stock_details.qty_out end) as openingOut'),
                    // DB::raw("SUM(godown_stock_details.qty_in < $fromDate) as total_qty"),
                )
                ->where('godown_stocks.type', 'GRN')
                ->where('godown_stock_details.type', 'GRN')
                ->where(function ($queryy) use ($supplier_id) {
                    if ($supplier_id != 0) {
                        $queryy->where('parties.id', $supplier_id);
                    }
                })
                ->where(function ($queryy) use ($purchaser_id) {
                    if ($purchaser_id != 0) {
                        $queryy->where('purchasers.id', $purchaser_id);
                    }
                })
                ->where(function ($queryy) use ($product_id) {
                    if ($product_id != 0) {
                        $queryy->where('godown_stock_details.product_id', $product_id);
                    }
                })
                // ->groupBy('godown_stocks.voucher_no')
                // ->groupBy('godown_stock_details.voucher_no')
                // ->where('to_warehouse_id',$warehouse_id)
                
                ->whereDate('godown_stocks.date', '>=', $fromDate)
                ->whereDate('godown_stocks.date', '<=', $toDate)
                ->get();

                return response()->json(['data'=> $detailReport]);
            }
        // }
        
    }

    public function PrintReport(Request $request){
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $warehouse_id=$request->WarehouseID;
        $product_id = $request->productID;
            // return "d";
            // $supplier_id = $request->supplier_id;
        $purchaser_id = $request->purchaserID;
        $supplier_id = $request->supplierID;
        $reportType=$request->report_type;
        $warehouse = Warehouse::where('id', $warehouse_id)->first();
        $supplier = Party::where('id', $supplier_id)->first(['code', 'party_name']);
        $purchaser = Party::where('id', $purchaser_id)->first(['code', 'party_name']);
        $product = Product::where('id', $product_id)->first(['code', 'product_name']);
        if($reportType=='summary'){
            // $summaryReport=Grn::with('warehouse:id,name')
            // ->where('warehouse_id',$warehouse_id)
            // ->whereDate('voucher_date', '>=', $fromDate)
            // ->whereDate('voucher_date', '<=', $toDate)
            // ->get();
            // return "dner";
           

            $summaryReport=GodownStock::join('godown_stock_details', 'godown_stock_details.transaction_id', '=', 'godown_stocks.id')
            ->join('warehouses', 'warehouses.id', '=', 'godown_stocks.to_warehouse_id')
            ->join('inward_gate_passes', 'inward_gate_passes.id', '=', 'godown_stocks.inward_gatepass_id')
            ->join('parties', 'parties.id', '=', 'inward_gate_passes.supplier_id')
            ->join('parties as purchasers', 'purchasers.id', '=', 'inward_gate_passes.purchaser_id')
            // ->with('warehouse:id,name')
            ->select(
                'godown_stocks.date as date',
                'godown_stocks.voucher_no',
                'warehouses.name as warehouse_name',
                'parties.party_name as supplier',
                'purchasers.party_name as purchaser',
                DB::raw("SUM(godown_stock_details.qty_in) as total_qty"),
                DB::raw("SUM(godown_stock_details.demand_qty) as demand_qty"),
                // DB::raw('sum(case when godown_stock_details.demand_qty < "'.$fromDate.' as DATE" then godown_stock_details.qty_out end) as openingOut'),
                // DB::raw("SUM(godown_stock_details.qty_in < $fromDate) as total_qty"),
            )
            ->where('godown_stocks.type', 'GRN')
            ->where('godown_stock_details.type', 'GRN')
            ->where(function ($queryy) use ($supplier_id) {
                if ($supplier_id != 0) {
                    $queryy->where('parties.id', $supplier_id);
                }
            })
            ->where(function ($queryy) use ($purchaser_id) {
                if ($purchaser_id != 0) {
                    $queryy->where('purchasers.id', $purchaser_id);
                }
            })
            ->where(function ($queryy) use ($product_id) {
                if ($product_id != 0) {
                    $queryy->where('godown_stock_details.product_id', $product_id);
                }
            })
            ->groupBy('godown_stocks.voucher_no')
            ->groupBy('godown_stock_details.voucher_no')
            // ->where('to_warehouse_id',$warehouse_id)
            
            ->whereDate('godown_stocks.date', '>=', $fromDate)
            ->whereDate('godown_stocks.date', '<=', $toDate)
            ->get();

            $pdf = PDF::loadView('grn.report.print', compact('summaryReport', 'warehouse', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product'));
            $fileName =  'grn-report.pdf';
            $pdf->save(base_path('upload/grn/' . $fileName));
            return $fileName;
            //  return response()->json(['data'=> $summaryReport]);
        
         }
         else if($reportType=='detail')
        {
            // $detailReport=GRNDetails::with('warehouse:id,name','product:id,product_name,code,uom')
            //     ->where('warehouse_id',$warehouse_id)
            //     ->whereDate('voucher_date', '>=', $fromDate)
            //     ->whereDate('voucher_date', '<=', $toDate)
            //     ->get();

            // $summaryReport=GodownStockDetail::with('warehouse:id,name','product:id,product_name,code,uom')
            // ->with(['godownstock' => function($query){
            //     $query->with(['inward_gatepass' => function($query){
            //         $query->with('supplier:id,party_name', 'purchaser:id,party_name');
            //     }]);
            // }])
            // ->where('warehouse_id',$warehouse_id)
            // ->where('type', 'GRN')
            // ->whereDate('date', '>=', $fromDate)
            // ->whereDate('date', '<=', $toDate)
            // ->get();

            $summaryReport=GodownStock::join('godown_stock_details', 'godown_stock_details.transaction_id', '=', 'godown_stocks.id')
                ->join('warehouses', 'warehouses.id', '=', 'godown_stocks.to_warehouse_id')
                ->join('products', 'products.id', '=', 'godown_stock_details.product_id')
                ->join('inward_gate_passes', 'inward_gate_passes.id', '=', 'godown_stocks.inward_gatepass_id')
                ->join('parties', 'parties.id', '=', 'inward_gate_passes.supplier_id')
                ->join('parties as purchasers', 'purchasers.id', '=', 'inward_gate_passes.purchaser_id')
                // ->with('warehouse:id,name')
                ->select(
                    'godown_stocks.date as date',
                    'godown_stocks.voucher_no',
                    'products.code',
                    'products.product_name',
                    'products.uom',
                    'warehouses.name as warehouse_name',
                    'parties.party_name as supplier',
                    'purchasers.party_name as purchaser',
                    'godown_stock_details.qty_in as qty_in',
                    'godown_stock_details.demand_qty as demand_qty',
                    // DB::raw("SUM(godown_stock_details.qty_in) as total_qty"),
                    // DB::raw("SUM(godown_stock_details.demand_qty) as demand_qty"),
                    // DB::raw('sum(case when godown_stock_details.demand_qty < "'.$fromDate.' as DATE" then godown_stock_details.qty_out end) as openingOut'),
                    // DB::raw("SUM(godown_stock_details.qty_in < $fromDate) as total_qty"),
                )
                ->where('godown_stocks.type', 'GRN')
                ->where('godown_stock_details.type', 'GRN')
                ->where(function ($queryy) use ($supplier_id) {
                    if ($supplier_id != 0) {
                        $queryy->where('parties.id', $supplier_id);
                    }
                })
                ->where(function ($queryy) use ($purchaser_id) {
                    if ($purchaser_id != 0) {
                        $queryy->where('purchasers.id', $purchaser_id);
                    }
                })
                ->where(function ($queryy) use ($product_id) {
                    if ($product_id != 0) {
                        $queryy->where('godown_stock_details.product_id', $product_id);
                    }
                })
                // ->groupBy('godown_stocks.voucher_no')
                // ->groupBy('godown_stock_details.voucher_no')
                // ->where('to_warehouse_id',$warehouse_id)
                
                ->whereDate('godown_stocks.date', '>=', $fromDate)
                ->whereDate('godown_stocks.date', '<=', $toDate)
                ->get();

            $pdf = PDF::loadView('grn.report.detail', compact('summaryReport', 'warehouse', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product'))->setPaper('a4', 'Landscape');
            $fileName =  'grn-report-detail.pdf';
            $pdf->save(base_path('upload/grn/' . $fileName));
            return $fileName;
    }

    }
}
