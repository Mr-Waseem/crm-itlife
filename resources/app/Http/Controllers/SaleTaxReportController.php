<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Product;
use App\Models\SalePurchase;
use App\Models\SalePurchaseDetail;
use App\Models\SaleTax;
use App\Models\Setting;
use App\Models\Warehouse;
use DB;
use PDF;
use Auth;

class SaleTaxReportController extends Controller
{
    public function index(Request $request)
    {
        // $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        // ->where('voucher_name', 'SALESTAX REPORT')
        // ->where('right_name', 'PRINT')
        // ->first();
        // if (!$voucherRight) {
        //     return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        // }
        $parties = Party::where('account_type', 'CUSTOMER')
        ->select(DB::raw(
            'CONCAT(`id`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            // ->where('account_type', "!=", 'AGENT')
            ->where('type', 'Registered')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('ALL CUSTOMERS', '0');

            $usertype = Auth::User()->role;
            if($usertype == "Admin"){
                $warehouse = Warehouse::OrderBy('id')->pluck('name', 'id')->prepend('All Warehouses', '0');
            }else{
                $warehouse = Warehouse::where('id', Auth::User()->warehouse_id)
                ->pluck('name', 'id');
            }
            $products = Product::pluck('product_name', 'id')->prepend('All Products', 0);
            $salestype = ['0' => 'All SALES', 'SALESTAX INVOICE' => 'SALESTAX REPORT', 'DIRECT SALESTAX INVOICE' => 'DIRECT SALESTAX REPORT', 'SALESTAX RETURN' => 'SALESTAX RETURN REPORT'];
        return view('saletax-invoice.report.index', compact('parties', 'warehouse', 'salestype', 'products'));
    }

    public function create()
    {
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        //return $suppliers;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('salestax-report.all-party.create', Compact('encrypted_token', 'parties'));
    }

    public function store(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        //return $supplier;
        $sales = SaleTax::with('saletax_details')->with('parties')->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        //return $sales;
        //$suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('salestax-report.all-party.index', compact('sales', 'encrypted_token', 'suppliers', 'company_detail', 'fromDate', 'toDate'));
    }

    public function SingleParty()
    {
        //return "ehsks";
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        //return $suppliers;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('salestax-report.single-party.create', Compact('encrypted_token', 'parties'));
    }

    public function ShowSingleParty(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $partyID = $request->get('party_name');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        //return $supplier;
        $sales = SaleTax::with('saletax_details')->with('parties')
            ->where('party_id', '=', $partyID)
            ->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        $party = Party::where('id', '=', $partyID)->get();
        //return $sales;
        //$suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('salestax-report.single-party.index', compact('sales', 'encrypted_token', 'suppliers', 'company_detail', 'fromDate', 'toDate', 'party'));
    }

    public function PrintReport(Request $request){
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $party_id = $request->customer_id;
        $reportType = $request->report_type;
        $warehouseID = $request->warehouseID;
       $saleType = $request->saleType;
       $productID = $request->productID;
        if ($reportType == 'summary') {
             $summaryReport = SalePurchaseDetail::join('products', 'products.id', '=', 'sale_purchase_details.product_id')
             ->join('parties', 'parties.id', '=', 'sale_purchase_details.party_id')
             ->join('sale_purchases', 'sale_purchases.id', '=', 'sale_purchase_details.sale_purchase_id')
             ->select(
                 'parties.party_name',
                 'parties.ntn',
                 DB::raw('SUM(sale_purchase_details.qty) as qty'),
                 DB::raw('SUM(sale_purchase_details.sale_tax) as taxvalue'),
                 DB::raw('SUM(sale_purchase_details.excl_val) as excl_val'),
                 DB::raw('SUM(sale_purchase_details.total) as total'),
                 'sale_purchase_details.date',
                 'sale_purchase_details.voucher_no',
                 'sale_purchase_details.type',
             )
             // ->where('sale_purchase_details.party_id', $party_id)
             ->where(function ($query) use ($productID) {
                if ($productID != 0) {
                    $query->where('sale_purchase_details.product_id', $productID);
                }
            })
            ->where(function ($query) use ($saleType) {
                if ($saleType != 0) {
                    $query->where('sale_purchases.type', $saleType);
                }
            })
             ->where(function ($query) use ($party_id) {
                 if ($party_id != 0) {
                     $query->where('sale_purchase_details.party_id', $party_id);
                 }
             })
             ->where(function ($query) use ($warehouseID) {
                if ($warehouseID != 0) {
                    $query->where('sale_purchase_details.warehouse_id', $warehouseID);
                }
            })
            ->where(function ($query) {
                $query->where('sale_purchases.type', 'SALESTAX INVOICE')
                        ->orWhere('sale_purchases.type', 'DIRECT SALESTAX INVOICE')
                        ->orWhere('sale_purchases.type', 'SALESTAX RETURN');
            })
             ->whereDate('sale_purchase_details.date', '>=', $fromDate)
             ->whereDate('sale_purchase_details.date', '<=', $toDate)
             ->groupBy('sale_purchases.id')
            //  ->orderBy('parties.id')
             ->OrderBy('voucher_no', 'asc')
             ->get();
            //  $warehouse = Warehouse::where('id', $warehouse_id)->first();
            $customer = Party::where('id',  $party_id)->first();
            // return $customer;
             $pdf = PDF::loadView('saletax-invoice.report.summary', compact('summaryReport', 'fromDate', 'toDate', 'customer', 'party_id'))->setPaper('a4', 'landscape');
             $fileName =  'SalesTax-Report.pdf';
             $pdf->save(base_path('upload/sales-voucher/' . $fileName));
             return $fileName;
        //  return response()->json(['data' => $summaryReport]);
     }
     if ($reportType == 'detailed') {               
        $summaryReport = SalePurchase::with(['sale_purchase_details' => function($query) use ($productID){
            $query->with('product:id,product_name,code,uom');
            $query->where(function ($query) use ($productID) {
                if ($productID != 0) {
                    $query->where('sale_purchase_details.product_id', $productID);
                }
            });
        }])->with('party')
        ->where(function ($query) use ($saleType) {
            if ($saleType != 0) {
                $query->where('sale_purchases.type', $saleType);
            }
        })
        ->where(function ($query) use ($party_id) {
            if ($party_id != 0) {
                $query->where('party_id', $party_id);
            }
        })
        ->where(function ($query) use ($warehouseID) {
            if ($warehouseID != 0) {
                $query->where('warehouse_id', $warehouseID);
            }
        })
        ->where(function ($query) {
            $query->where('type', 'SALESTAX INVOICE')
                    ->orWhere('type', 'DIRECT SALESTAX INVOICE')
                    ->orWhere('type', 'SALESTAX RETURN');
        })
        ->whereDate('date', '>=', $fromDate)
        ->whereDate('date', '<=', $toDate)
        ->OrderBy('voucher_no', 'asc')->get();

        $customer = Party::where('id',  $party_id)->first();
            // return $customer;
        $pdf = PDF::loadView('saletax-invoice.report.detail', compact('summaryReport', 'fromDate', 'toDate', 'customer', 'party_id'))->setPaper('a4', 'landscape');
        $fileName =  'SalesTax-Report.pdf';
        $pdf->save(base_path('upload/sales-voucher/' . $fileName));
        return $fileName;

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
        //
    }

    public function destroy($id)
    {
        //
    }
}
