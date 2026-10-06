<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\Vouchers;
use App\Models\Warehouse;
use App\Models\CustomerProduct;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class CashBookSingleDateController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function CustomerReport(Request $request)
    {
        // return "D";
         $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'CASH BOOK SINGLE DATE')
        ->where('right_name', 'ADD')
        ->first();
        if (!$voucherRight) {
            // return "Ds";
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            
        }
        if ($request->ajax()) {

             $fromDate = $request->from_date;
            // $toDate = $request->to_date;
            $report_type = $request->report_type;
            $customer_id = $request->customer_id;
            $warehouseID = $request->warehouseID;

            $OpeningcustomerLedger = DB::table('general_vouchers')
                    ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                    ->whereDate('date', '<', $fromDate)
                    // ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    ->where('warehouse_id', $warehouseID)
                    ->first();

            if ($report_type == 'summary') {
                // return "Ds";
                 $customerLedger = DB::table('general_vouchers')
                    //->join('account_groups', 'account_groups.id', '=', 'general_vouchers.account_head_id')
                    ->join('parties', 'parties.id', '=', 'general_vouchers.other_head_id')
                    ->join('warehouses', 'warehouses.id', '=', 'general_vouchers.warehouse_id')
                    ->select('general_vouchers.voucher_no', 'warehouses.name', 'general_vouchers.v_type', 'general_vouchers.date', 'parties.code','parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'), DB::raw('SUM(general_vouchers.credit) as credit'))
                    // ->groupBy('voucher_id')
                    ->where(function ($query) use ($warehouseID) {
                        if ($warehouseID != 0) {
                            $query->where('general_vouchers.warehouse_id', $warehouseID);
                            }
                        })
                    ->whereDate('general_vouchers.date', '=', $fromDate)
                    // ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('general_vouchers.account_head_id', $customer_id)
                    ->groupBy('general_vouchers.voucher_id')
                    ->orderBy('general_vouchers.date', 'asc')
                    ->orderBy('general_vouchers.id', 'asc')
                    // ->whereBetween('sale_details.created_at', [$fromDate, $toDate])
                    ->get()->toArray();
                if($customerLedger){
                    // return Response::json(['data' => $customerLedger]);
                    return Response::json ([
                        'data' => $customerLedger,
                        'OpeningcustomerLedger' => $OpeningcustomerLedger
                    ]);
                } else {
                    // return Response::json(['data' => '']);
                    return Response::json ([
                        'data' => '',
                        'OpeningcustomerLedger' => $OpeningcustomerLedger
                    ]);
                }

                // return Response::json(['data' => $customerLedger]);
            }
            if ($report_type == 'detailed') {
                $DeliveryChallan = CustomerProduct::where('customer_id', $customer_id)->count();
               if($DeliveryChallan > 0){
                $status = '0';
                $customerLedger = GeneralVoucher::with('customer_products')->with('warehouse')
                ->with('other_parties')
                ->with('products')
                ->where(function ($query) use ($warehouseID) {
                    if ($warehouseID != 0) {
                        $query->where('warehouse_id', $warehouseID);
                        }
                    })
                ->whereDate('date', '=', $fromDate)
                // ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
                if($customerLedger){
                    // return Response::json(['data' => $customerLedger]);
                    return Response::json ([
                        'data' => $customerLedger,
                        'status' => $status,
                        'OpeningcustomerLedger' => $OpeningcustomerLedger
                    ]);
                }else {
                    return Response::json ([
                        'data' => '',
                        'OpeningcustomerLedger' => $OpeningcustomerLedger
                    ]);
                }
               }else{
                $status = '1';
                $customerLedger = GeneralVoucher::with('other_parties')->with('products')->with('warehouse')
                ->where(function ($query) use ($warehouseID) {
                    if ($warehouseID != 0) {
                        $query->where('warehouse_id', $warehouseID);
                        }
                    })
                ->whereDate('date', '=', $fromDate)
                // ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                // ->whereStatus(1)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();
                if($customerLedger){
                    return Response::json ([
                        'data' => $customerLedger,
                        'status' => $status,
                        'OpeningcustomerLedger' => $OpeningcustomerLedger
                    ]);
                }else {
                        return Response::json ([
                            'data' => '',
                            'OpeningcustomerLedger' => $OpeningcustomerLedger
                        ]);
                    }
               }
              
            }
        }


        //  $parties = Party::OrderBy('party_name', 'asc')
        //     // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
        //     ->where('account_group_id3', 1)
        //     ->pluck('party_name', 'id')
        //     ->prepend('Select Cash Account', '');


             $parties = Party::
        // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            select(DB::raw(
            'CONCAT(`id`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->where('account_group_id3', 1)
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
            $warehouse = Warehouse::OrderBy('id', 'asc')->pluck('name', 'id')->prepend('All Warehouses', '0');
            $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        return view('customer-reports.cash-book-single.index', compact('parties', 'warehouse', 'SingleWarehouse'));
    }

    public function LedgerPDF(Request $request){
        // return "pdf";
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'CASH BOOK SINGLE DATE')
        ->where('right_name', 'PRINT')
        ->first();
        if (!$voucherRight) {
            // return "Ds";
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        $fromDate = $request->from_date;
        // $toDate = $request->to_date;
        $report_type = $request->report_type;
        $customer_id = $request->Customerid;
        $warehouseID = $request->warehouseID;
        $customer = Party::where('id', $customer_id)->first();
        $OpeningcustomerLedger = DB::table('general_vouchers')
                ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                ->whereDate('date', '<', $fromDate)
                // ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->where('warehouse_id', $warehouseID)
                ->first();

        if ($report_type == 'summary') {
            $customerLedger = DB::table('general_vouchers')
                    //->join('account_groups', 'account_groups.id', '=', 'general_vouchers.account_head_id')
                    ->join('parties', 'parties.id', '=', 'general_vouchers.other_head_id')
                    ->join('warehouses', 'warehouses.id', '=', 'general_vouchers.warehouse_id')
                    ->select('general_vouchers.voucher_no', 'warehouses.name', 'general_vouchers.v_type', 'general_vouchers.date', 'parties.code','parties.party_name', DB::raw('SUM(general_vouchers.debit) as debit'), DB::raw('SUM(general_vouchers.credit) as credit'))
                    // ->groupBy('voucher_id')
                    ->where(function ($query) use ($warehouseID) {
                        if ($warehouseID != 0) {
                            $query->where('general_vouchers.warehouse_id', $warehouseID);
                            }
                        })
                    ->whereDate('general_vouchers.date', '=', $fromDate)
                    // ->whereDate('general_vouchers.date', '<=', $toDate)
                    ->where('general_vouchers.account_head_id', $customer_id)
                    ->groupBy('general_vouchers.voucher_id')
                    ->orderBy('general_vouchers.date', 'asc')
                    ->orderBy('general_vouchers.id', 'asc')
                    // ->whereBetween('sale_details.created_at', [$fromDate, $toDate])
                    ->get()->toArray();

        $pdf = PDF::loadView('customer-reports.cash-book-single.summary-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate'));
        $fileName =  'Cash-Book-Summary.pdf';
        $pdf->save(base_path('upload/ledger/' . $fileName));
        return $fileName;
        }
        if ($report_type == 'detailed') {
                $customerLedger = GeneralVoucher::with('other_parties')->with('products')->with('warehouse')
                ->where(function ($query) use ($warehouseID) {
                    if ($warehouseID != 0) {
                        $query->where('warehouse_id', $warehouseID);
                        }
                    })
                ->whereDate('date', '=', $fromDate)
                // ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

                $pdf = PDF::loadView('customer-reports.cash-book-single.detail-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate'));
                $fileName =  'Cash-Book-Detail.pdf';
                $pdf->save(base_path('upload/ledger/' . $fileName));
                return $fileName;
               
        }

}


    public function CustomerBalance(Request $request)
    {
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('Accumulated (All)', '');
        return view('customer-reports.cash-book-single.balance', compact('warehouse'));
    }

    public function CustomerAging(Request $request)
    {
        $customers = Party::orderBy('party_name')->pluck('party_name', 'id')->prepend('Select Customer', '');
        return view('customer-reports.cash-book-single.aging', compact('customers'));
    }
}
