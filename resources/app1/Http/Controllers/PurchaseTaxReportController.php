<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\Product;
use App\Models\SalePurchase;
use App\Models\SalePurchaseDetail;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
// use Yajra\DataTables\Facades\DataTables;

class PurchaseTaxReportController extends Controller
{
    public function index(Request $request)
    {
        // return "d";
         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'PURCHASE TAX REPORT')
        ->where('right_name', 'PRINT')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $supplier = Party::OrderBy('party_name', 'asc')
            // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
            ->where('account_type', 'SUPPLIER')
            // ->Orwhere('account_type', 'PURCHASER')
            ->pluck('party_name', 'id')
            ->prepend('All Suppliers', '0');
        // return view('purchases.report.index', compact('parties'));

        $products = Product::pluck('product_name', 'id')->prepend('All Products', 0);
            // $supplier = Party::where('account_type', 'Supplier')->pluck('party_name', 'id')->prepend('All Suppliers', 0);
        $purchaser = Party::where('account_type', 'Purchaser')->pluck('party_name', 'id')->prepend('All Purchasers', 0);
        return view('purchases.purchase-tax.report.index', compact('products', 'supplier', 'purchaser'));
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
                    $PurchaseSummary = SalePurchase::join('sale_purchase_details', 'sale_purchase_details.sale_purchase_id', '=', 'sale_purchases.id')
                         ->join('parties as supplier', 'supplier.id', '=', 'sale_purchases.party_id')
                        ->join('parties as purchaser', 'purchaser.id', '=', 'sale_purchases.purchaser_id')
                        ->join('godown_stocks', 'godown_stocks.id', '=', 'sale_purchases.grn_dc_id')
                        ->select(
                            'sale_purchases.date as date',
                            'sale_purchases.voucher_no',
                            'godown_stocks.voucher_no as grnNo',
                            'sale_purchases.credit_to',
                            'supplier.party_name as supplier_name',
                            'purchaser.party_name as purchaser_name',
                            DB::raw('SUM(sale_purchase_details.qty) as qty'),
                            DB::raw('SUM(sale_purchase_details.rate) as rate'),
                            DB::raw('SUM(sale_purchase_details.excl_val) as excl_val'),
                            DB::raw('SUM(sale_purchase_details.st_rate) as st_rate'),
                            DB::raw('SUM(sale_purchase_details.sale_tax) as sale_tax'),
                            DB::raw('SUM(sale_purchase_details.total) as total')
                        )
                        ->whereDate('sale_purchases.date', '>=', $fromDate)
                        ->whereDate('sale_purchases.date', '<=', $toDate)
                        ->where('sale_purchases.type', 'PURCHASETAX')
                        ->where(function ($queryy) use ($supplier_id) {
                            if ($supplier_id != 0) {
                                $queryy->where('sale_purchases.party_id', $supplier_id);
                            }
                        })
                        ->where(function ($queryy) use ($purchaser_id) {
                            if ($purchaser_id != 0) {
                                $queryy->where('sale_purchases.purchaser_id', $purchaser_id);
                            }
                        })
                        ->where(function ($queryy) use ($product_id) {
                            if ($product_id != 0) {
                                $queryy->where('sale_purchase_details.product_id', $product_id);
                            }
                        })
                        ->groupBy('sale_purchases.voucher_no')
                        ->orderBy('sale_purchases.voucher_no', 'asc')
                        ->get();

                    return Response::json(['data' => $PurchaseSummary]);

            }
            else
            if ($reportType == 'detail') {
                    $PurchaseDetail = SalePurchaseDetail::join('sale_purchases', 'sale_purchases.id', '=', 'sale_purchase_details.sale_purchase_id')
                         ->join('parties as supplier', 'supplier.id', '=', 'sale_purchases.party_id')
                        ->join('parties as purchaser', 'purchaser.id', '=', 'sale_purchases.purchaser_id')
                        ->join('godown_stocks', 'godown_stocks.id', '=', 'sale_purchases.grn_dc_id')
                        ->join('products', 'products.id', '=', 'sale_purchase_details.product_id')
                        ->select(
                            'sale_purchases.date as date',
                            'sale_purchases.voucher_no',
                            'godown_stocks.voucher_no as grnNo',
                            'sale_purchases.credit_to',
                            'supplier.party_name as supplier_name',
                            'purchaser.party_name as purchaser_name',
                            'products.code',
                            'products.product_name',
                            'sale_purchase_details.qty',
                            'sale_purchase_details.rate',
                            'sale_purchase_details.excl_val',
                            'sale_purchase_details.st_rate',
                            'sale_purchase_details.sale_tax',
                            'sale_purchase_details.total',
                            

                            // 'products.uom as uom',
                            // DB::raw('SUM(sale_purchase_details.qty) as qty'),
                            // DB::raw('SUM(sale_purchase_details.rate) as rate'),
                            // DB::raw('SUM(sale_purchase_details.total) as total')
                        )
                        ->whereDate('sale_purchases.date', '>=', $fromDate)
                        ->whereDate('sale_purchases.date', '<=', $toDate)
                        ->where('sale_purchases.type', 'PURCHASETAX')
                        ->where(function ($queryy) use ($supplier_id) {
                            if ($supplier_id != 0) {
                                $queryy->where('sale_purchases.party_id', $supplier_id);
                            }
                        })
                        ->where(function ($queryy) use ($purchaser_id) {
                            if ($purchaser_id != 0) {
                                $queryy->where('sale_purchases.purchaser_id', $purchaser_id);
                            }
                        })
                        ->where(function ($queryy) use ($product_id) {
                            if ($product_id != 0) {
                                $queryy->where('sale_purchase_details.product_id', $product_id);
                            }
                        })
                        // ->groupBy('sale_purchases.voucher_no')
                        ->orderBy('sale_purchases.voucher_no', 'asc')
                        ->get();
                return Response::json(['data' => $PurchaseDetail]);
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
                $PurchaseSummary = SalePurchase::join('sale_purchase_details', 'sale_purchase_details.sale_purchase_id', '=', 'sale_purchases.id')
                     ->join('parties as supplier', 'supplier.id', '=', 'sale_purchases.party_id')
                    ->join('parties as purchaser', 'purchaser.id', '=', 'sale_purchases.purchaser_id')
                    ->join('godown_stocks', 'godown_stocks.id', '=', 'sale_purchases.grn_dc_id')
                    ->select(
                        'sale_purchases.date as date',
                        'sale_purchases.voucher_no',
                        'godown_stocks.voucher_no as grnNo',
                        'sale_purchases.credit_to',
                        'supplier.party_name as supplier_name',
                        'purchaser.party_name as purchaser_name',
                        DB::raw('SUM(sale_purchase_details.qty) as qty'),
                        DB::raw('SUM(sale_purchase_details.rate) as rate'),
                        DB::raw('SUM(sale_purchase_details.excl_val) as excl_val'),
                        DB::raw('SUM(sale_purchase_details.st_rate) as st_rate'),
                        DB::raw('SUM(sale_purchase_details.sale_tax) as sale_tax'),
                        DB::raw('SUM(sale_purchase_details.total) as total')
                    )
                    ->whereDate('sale_purchases.date', '>=', $fromDate)
                    ->whereDate('sale_purchases.date', '<=', $toDate)
                    ->where('sale_purchases.type', 'PURCHASETAX')
                    ->where(function ($queryy) use ($supplier_id) {
                        if ($supplier_id != 0) {
                            $queryy->where('sale_purchases.party_id', $supplier_id);
                        }
                    })
                    ->where(function ($queryy) use ($purchaser_id) {
                        if ($purchaser_id != 0) {
                            $queryy->where('sale_purchases.purchaser_id', $purchaser_id);
                        }
                    })
                    ->where(function ($queryy) use ($product_id) {
                        if ($product_id != 0) {
                            $queryy->where('sale_purchase_details.product_id', $product_id);
                        }
                    })
                    ->groupBy('sale_purchases.voucher_no')
                    ->orderBy('sale_purchases.voucher_no', 'asc')
                    ->get();

                    $pdf = PDF::loadView('purchases.purchase-tax.report.summary', compact('PurchaseSummary', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product'))->setPaper('a4', 'landscape');
                    $fileName =  'Purchase Report Summary.pdf';
                    $pdf->save(base_path('upload/purchase/' . $fileName));
                    return $fileName;

                return Response::json(['data' => $PurchaseSummary]);

        }
        else
        if ($reportType == 'detail') {
                $PurchaseDetail = SalePurchaseDetail::join('sale_purchases', 'sale_purchases.id', '=', 'sale_purchase_details.sale_purchase_id')
                     ->join('parties as supplier', 'supplier.id', '=', 'sale_purchases.party_id')
                    ->join('parties as purchaser', 'purchaser.id', '=', 'sale_purchases.purchaser_id')
                    ->join('godown_stocks', 'godown_stocks.id', '=', 'sale_purchases.grn_dc_id')
                    ->join('products', 'products.id', '=', 'sale_purchase_details.product_id')
                    ->select(
                        'sale_purchases.date as date',
                        'sale_purchases.voucher_no',
                        'godown_stocks.voucher_no as grnNo',
                        'sale_purchases.credit_to',
                        'supplier.party_name as supplier_name',
                        'purchaser.party_name as purchaser_name',
                        'products.code',
                        'products.product_name',
                        'sale_purchase_details.qty',
                        'sale_purchase_details.rate',
                        'sale_purchase_details.excl_val',
                        'sale_purchase_details.st_rate',
                        'sale_purchase_details.sale_tax',
                        'sale_purchase_details.total',
                        // 'products.uom as uom',
                        // DB::raw('SUM(sale_purchase_details.qty) as qty'),
                        // DB::raw('SUM(sale_purchase_details.rate) as rate'),
                        // DB::raw('SUM(sale_purchase_details.total) as total')
                    )
                    ->whereDate('sale_purchases.date', '>=', $fromDate)
                    ->whereDate('sale_purchases.date', '<=', $toDate)
                    ->where('sale_purchases.type', 'PURCHASETAX')
                    ->where(function ($queryy) use ($supplier_id) {
                        if ($supplier_id != 0) {
                            $queryy->where('sale_purchases.party_id', $supplier_id);
                        }
                    })
                    ->where(function ($queryy) use ($purchaser_id) {
                        if ($purchaser_id != 0) {
                            $queryy->where('sale_purchases.purchaser_id', $purchaser_id);
                        }
                    })
                    ->where(function ($queryy) use ($product_id) {
                        if ($product_id != 0) {
                            $queryy->where('sale_purchase_details.product_id', $product_id);
                        }
                    })
                    // ->groupBy('sale_purchases.voucher_no')
                    ->orderBy('sale_purchases.voucher_no', 'asc')
                    ->get();

                    $pdf = PDF::loadView('purchases.purchase-tax.report.detail', compact('PurchaseDetail', 'fromDate', 'toDate', 'supplier', 'purchaser', 'product'))->setPaper('a4', 'landscape');
                    $fileName =  'Purchase Report Detail.pdf';
                    $pdf->save(base_path('upload/purchase/' . $fileName));
                    return $fileName;
            return Response::json(['data' => $PurchaseDetail]);
        }

       
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        return "hel";
    }

    public function destroy($id)
    {
        //
    }
}
