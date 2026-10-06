<?php

namespace App\Http\Controllers;
use App\Models\VoucherRights;
use App\Models\SaleOrder;
use App\Models\Party;
use App\Models\Product;
use App\Models\DeliveryChallanDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
class DCPOStatusReportController extends Controller
{
    public function index(Request $request)
    {
        // $usertype = Auth::User()->role;
        // if( $usertype == "Admin"){
        //     $warehouse = Warehouse::pluck('name', 'id')->prepend('All Warehouses', '0');
        // }else{
        //     $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        // }
         
        //  return $warehouse;
        $po = SaleOrder::where('type', 'SALE ORDER')->pluck('po_no', 'po_no')->prepend('Select PO', '0');
        $parties = Party::whereRole('Customer')->where('type', 'Registered')->pluck('party_name', 'id')->prepend('All Customer', 0);
        $products = Product::select(
            DB::raw('CONCAT(`code`, "-", `product_name`) AS `product_name`,
              `id`'
              ))
            ->where('warehouse_id', 11)
            ->OrderBy('id', 'asc')
            ->pluck('product_name', 'id')
            ->prepend('All Products', '0');
        return view('delivery-challan.dc-po-status-report.index', compact('parties', 'po', 'products'));
        
    }

    public function report(Request $request)
    {
        //  return $request;
        // if ($request->ajax()) {
            $partyID = $request->partyID;
            $productID = $request->productID;
            $Fromdate = $request->from_date;
            $Todate = $request->to_date;
            $PoNo = $request->PONo;
            $reportType = $request->report_type;
            //  return $request;
            // if ($supplier_id != 0) {
            //     $supplier = Party::find($supplier_id);
            // }
            // if ($purchaser_id != 0) {
            //     $purchaser = Party::find($purchaser_id);
            // }
            if ($reportType == 'summary') {
            $dcdata = DeliveryChallanDetails::
            // with('product')->with('party')
            // ->
            join('products', 'products.id', '=', 'delivery_challan_details.product_id')
            ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
            ->join('sale_orders', 'sale_orders.voucher_no', '=', 'delivery_challan_details.sale_order_no')
            ->select(
                // 'delivery_challan_details.voucher_date as voucher_date',
                // 'delivery_challan_details.voucher_no',
                'products.code',
                'products.product_name as product_name',
                'parties.party_name as party_name',
                // 'sale_orders.voucher_no',
                'sale_orders.po_date',
                'delivery_challan_details.po_no',
                'delivery_challan_details.demandPCS',
                DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty')
            )
            ->where('delivery_challan_details.po_no', $PoNo)
        //    ->whereDate('delivery_challan_details.voucher_date', '>=', $Fromdate)
        //    ->whereDate('delivery_challan_details.voucher_date', '<=', $Todate)
           
           // ->where('request_generate_details.product_id', $product_id)
        //    ->where(function ($queryy) use ($WarehouseID) {
        //        if ($WarehouseID != 0) {
        //            $queryy->where('request_generate_details.warehouse_id', $WarehouseID);
        //        }
        //    })

           ->groupBy('delivery_challan_details.product_id')
           ->orderBy('delivery_challan_details.id', 'asc')
           ->get();
            return Response::json(['data' => $dcdata]);
        }
            else
            if ($reportType == 'detail') {
                //   return $productID;
        

        $dcdata = SaleOrder::with(['sale_order_details' => function($query) use ($productID){
            $query->where(function ($query1) use ($productID) {
                if ($productID != 0) {
                    $query1->where('product_id', $productID);
                }
            });

            $query->with('product:id,code,product_name');
            $query->with('dc_details2');
        }])
        // ->where(function ($query) use ($productID) {
        //     if ($productID != 0) {
        //         $query->where('product_id', $productID);
        //     }
        // })
        ->where('po_no', $PoNo)
        ->where('type', 'SALE ORDER')
        ->get();
            return Response::json(['data' => $dcdata]);
        //    return Response::json(['data' => $ReqGeneReportSummary]);
            }
    }

    public function loadPO(Request $request){
        // return $request;
        $data = DeliveryChallanDetails::where('party_id', $request->partyID)
        ->groupBy('po_no')
        ->get();
        return Response::json(['data' => $data]);
        // return $data;
    }

    public function loadProduct(Request $request){
        // return $request;
        $data = SaleOrderDetails::join('products', 'products.id', '=', 'sale_order_details.product_id')
        ->join('sale_orders', 'sale_orders.id', '=', 'sale_order_details.sale_order_id')
        ->select(
            // 'delivery_challan_details.voucher_date as voucher_date',
            // 'delivery_challan_details.voucher_no',
            'products.id',
            'products.code',
            'products.product_name as product_name',
        )
        ->where('sale_orders.po_no', $request->poNo)
        ->groupBy('product_id')
        ->get();
        return Response::json(['data' => $data]);
        // return $data;
    }
    public function PrintReport(Request $request){
        // return $request;
        // return $request;
        // if ($request->ajax()) {

            $partyID = $request->partyID;
            $productID = $request->productID;
            $Fromdate = $request->from_date;
            $Todate = $request->to_date;
            $PoNo = $request->PONo;
            $reportType = $request->report_type;
       
        //  return $request;
        // if ($supplier_id != 0) {
        //     $supplier = Party::find($supplier_id);
        // }
        // if ($purchaser_id != 0) {
        //     $purchaser = Party::find($purchaser_id);
        // }


        if ($reportType == 'summary') {
            $dcdata = DeliveryChallanDetails::
            // with('product')->with('party')
            // ->
            join('products', 'products.id', '=', 'delivery_challan_details.product_id')
            ->join('parties', 'parties.id', '=', 'delivery_challan_details.party_id')
            ->join('sale_orders', 'sale_orders.voucher_no', '=', 'delivery_challan_details.sale_order_no')
            ->select(
                // 'delivery_challan_details.voucher_date as voucher_date',
                // 'delivery_challan_details.voucher_no',
                'products.code',
                'products.product_name as product_name',
                'parties.party_name as party_name',
                // 'sale_orders.voucher_no',
                'sale_orders.po_date',
                'delivery_challan_details.po_no',
                'delivery_challan_details.demandPCS',
                DB::raw('SUM(delivery_challan_details.sale_qty) as sale_qty')
            )
            ->where('delivery_challan_details.po_no', $PoNo)
        //    ->whereDate('delivery_challan_details.voucher_date', '>=', $Fromdate)
        //    ->whereDate('delivery_challan_details.voucher_date', '<=', $Todate)
           
           // ->where('request_generate_details.product_id', $product_id)
        //    ->where(function ($queryy) use ($WarehouseID) {
        //        if ($WarehouseID != 0) {
        //            $queryy->where('request_generate_details.warehouse_id', $WarehouseID);
        //        }
        //    })

           ->groupBy('delivery_challan_details.product_id')
           ->orderBy('delivery_challan_details.id', 'asc')
           ->get();
    //    return Response::json(['data' => $dcdata]);

                $pdf = PDF::loadView('delivery-challan.dc-po-status-report.summary', compact('dcdata'))->setPaper('a4', 'Landscape');
                $fileName =  'DC PO Status Report Summary.pdf';
                $pdf->save(base_path('upload/delivery-challan/' . $fileName));
                return $fileName;
                // return Response::json(['data' => $ReqGeneReportSummary]);

        }
        else
        if ($reportType == 'detail') {

            $dcdata = SaleOrder::with(['sale_order_details' => function($query) use ($productID){
                $query->where(function ($query1) use ($productID) {
                    if ($productID != 0) {
                        $query1->where('product_id', $productID);
                    }
                });
                $query->with('product:id,code,product_name');
                $query->with('dc_details2');
            }])
            ->where('po_no', $PoNo)
            ->where('type', 'SALE ORDER')
            ->get();
           $pdf = PDF::loadView('delivery-challan.dc-po-status-report.detail', compact('dcdata'));
           $fileName =  'DC PO Status Report Detail.pdf';
           $pdf->save(base_path('upload/delivery-challan/' . $fileName));
           return $fileName;
        }
    }
}
