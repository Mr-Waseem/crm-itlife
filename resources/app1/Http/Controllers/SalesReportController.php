<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\SalePurchase;
use App\Models\SalePurchaseDetail;
use App\Models\GeneralVoucher;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use DB;
use PDF;
use Auth;
class SalesReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'SALES REPORT')
        ->where('right_name', 'PRINT')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $party_id = $request->customer_id;
            $reportType = $request->report_type;
            $warehouseID = $request->warehouseID;
            if ($reportType == 'summary') {
                   $summaryReport = SalePurchaseDetail::join('products', 'products.id', '=', 'sale_purchase_details.product_id')
                    ->join('parties', 'parties.id', '=', 'sale_purchase_details.party_id')
                    ->join('sale_purchases', 'sale_purchases.id', '=', 'sale_purchase_details.sale_purchase_id')
                    ->select(
                        'parties.party_name',
                        'sale_purchases.extra_charges',
                        'sale_purchases.extra_discount',
                        DB::raw('SUM(sale_purchase_details.qty) as qty'),
                        DB::raw('SUM(sale_purchase_details.rate) as rate'),
                        DB::raw('SUM(sale_purchase_details.discount) as discount'),
                        DB::raw('SUM(sale_purchase_details.total) as total'),
                        'sale_purchase_details.date',
                        'sale_purchase_details.voucher_no',
                    )
                    // ->where('sale_purchase_details.party_id', $party_id)
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
                    ->whereDate('sale_purchase_details.date', '>=', $fromDate)
                    ->whereDate('sale_purchase_details.date', '<=', $toDate)
                    ->groupBy('sale_purchases.id')
                    ->orderBy('parties.id')
                    ->get();
                return response()->json(['data' => $summaryReport]);
            }
            if ($reportType == 'detailed') {               
                    $detailReport = SalePurchase::with(['sale_purchase_details' => function($query){
                        $query->with('product:id,product_name,code,uom');
                    }])->with('party')
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
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->OrderBy('voucher_no', 'asc')->get();
                return response()->json(['data' => $detailReport]);
            }
        }
        $parties = Party::where('account_type', 'CUSTOMER')
        ->select(DB::raw(
            'CONCAT(`id`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            // ->whereNotIn('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            // ->where('account_type', "!=", 'AGENT')
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
        return view('sales.report.index', compact('parties', 'warehouse'));
    }


    public function PrintReport(Request $request){
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $party_id = $request->customer_id;
        $reportType = $request->report_type;
        $warehouseID = $request->warehouseID;

        if ($reportType == 'summary') {
            $summaryReport = SalePurchaseDetail::join('products', 'products.id', '=', 'sale_purchase_details.product_id')
             ->join('parties', 'parties.id', '=', 'sale_purchase_details.party_id')
             ->join('sale_purchases', 'sale_purchases.id', '=', 'sale_purchase_details.sale_purchase_id')
             ->select(
                 'parties.party_name',
                 'sale_purchases.extra_charges',
                 'sale_purchases.extra_discount',
                 DB::raw('SUM(sale_purchase_details.qty) as qty'),
                 DB::raw('SUM(sale_purchase_details.rate) as rate'),
                 DB::raw('SUM(sale_purchase_details.discount) as discount'),
                 DB::raw('SUM(sale_purchase_details.total) as total'),
                 'sale_purchase_details.date',
                 'sale_purchase_details.voucher_no',
             )
             // ->where('sale_purchase_details.party_id', $party_id)
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
             ->whereDate('sale_purchase_details.date', '>=', $fromDate)
             ->whereDate('sale_purchase_details.date', '<=', $toDate)
             ->groupBy('sale_purchases.id')
             ->orderBy('parties.id')
             ->get();
            //  $warehouse = Warehouse::where('id', $warehouse_id)->first();
            $customer = Party::where('id',  $party_id)->first();
            // return $customer;
             $pdf = PDF::loadView('sales.report.summary', compact('summaryReport', 'fromDate', 'toDate', 'customer', 'party_id'))->setPaper('a4', 'landscape');
             $fileName =  'Sales-Report.pdf';
             $pdf->save(base_path('upload/sales-voucher/' . $fileName));
             return $fileName;
        //  return response()->json(['data' => $summaryReport]);
     }
     if ($reportType == 'detailed') {               
        $summaryReport = SalePurchase::with(['sale_purchase_details' => function($query){
            $query->with('product:id,product_name,code,uom');
        }])->with('party')
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
        ->whereDate('date', '>=', $fromDate)
        ->whereDate('date', '<=', $toDate)
        ->OrderBy('voucher_no', 'asc')->get();

        $customer = Party::where('id',  $party_id)->first();
            // return $customer;
        $pdf = PDF::loadView('sales.report.detail', compact('summaryReport', 'fromDate', 'toDate', 'customer', 'party_id'))->setPaper('a4', 'landscape');
        $fileName =  'Sales-Report.pdf';
        $pdf->save(base_path('upload/sales-voucher/' . $fileName));
        return $fileName;

     }
    }
}
