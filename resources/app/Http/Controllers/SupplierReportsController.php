<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\Vouchers;
use App\Models\Warehouse;
use App\Models\CustomerProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use PDF;

class SupplierReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function CustomerReport(Request $request)
    {
        if ($request->ajax()) {

             $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $report_type = $request->report_type;
            $customer_id = $request->customer_id;

           $OpeningcustomerLedger = DB::table('general_vouchers')
                    ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                    ->whereDate('date', '<', $fromDate)
                    // ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    ->first();
            
            // Ensure OpeningcustomerLedger is not null
            if (!$OpeningcustomerLedger) {
                $OpeningcustomerLedger = (object)[
                    'debit' => 0,
                    'credit' => 0
                ];
            }

            if ($report_type == 'summary') {
                
                 $customerLedger = DB::table('general_vouchers')
                    //->join('account_groups', 'account_groups.id', '=', 'general_vouchers.account_head_id')
                    // ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                    ->select('voucher_no', 'v_type', 'date', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                    // ->groupBy('voucher_id')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    ->groupBy('voucher_id')
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
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
                $customerLedger = GeneralVoucher::with('customer_products')
                ->with('other_parties')
                ->with('products')
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
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
                $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
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


        $parties = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
        ->OrderBy('party_name', 'asc')
            // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
            ->where('account_type', 'SUPPLIER')
            ->Orwhere('account_type', 'PURCHASER')
            ->pluck('party_name', 'id')
            ->prepend('Select Supplier / Purchaser', '');

            // $parties = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            // ->OrderBy('party_name', 'asc')
            //     // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
            //     ->where('role', 'Customer')
            //     ->pluck('party_name', 'id')
            //     ->prepend('Select Customer', '');

        return view('customer-reports.supplier.ledger', compact('parties'));
    }

    public function LedgerPDF(Request $request){
        // return $request;
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $report_type = $request->report_type;
        $customer_id = $request->Customerid;
        $customer = Party::where('id', $customer_id)->first();
        $OpeningcustomerLedger = DB::table('general_vouchers')
                ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                ->whereDate('date', '<', $fromDate)
                // ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->first();
        
        // Ensure OpeningcustomerLedger is not null
        if (!$OpeningcustomerLedger) {
            $OpeningcustomerLedger = (object)[
                'debit' => 0,
                'credit' => 0
            ];
        }

        if ($report_type == 'summary') {
            $customerLedger = DB::table('general_vouchers')
                ->select('voucher_no', 'v_type', 'date', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->groupBy('voucher_id')
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();

            $pdf = PDF::loadView('customer-reports.supplier.summary-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate'));
            $fileName =  'Supplier-ledger.pdf';
            $pdf->save(base_path('upload/ledger/' . $fileName));
            return $fileName;
        }
        if ($report_type == 'detailed') {
              $DeliveryChallan = CustomerProduct::where('customer_id', $customer_id)->count();
             if($DeliveryChallan > 0){
                $status = '0';
                $customerLedger = GeneralVoucher::with('customer_products')
                ->with('other_parties')->with('products')
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

                $pdf = PDF::loadView('customer-reports.supplier.detail-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate', 'status'))->setPaper('a4', 'landscape');
                $fileName =  'Supplier-ledger.pdf';
                $pdf->save(base_path('upload/ledger/' . $fileName));
                return $fileName;

             }else{
                $status = '1';
                $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

                $pdf = PDF::loadView('customer-reports.supplier.detail-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate', 'status'))->setPaper('a4', 'landscape');
                $fileName =  'Supplier-ledger.pdf';
                $pdf->save(base_path('upload/ledger/' . $fileName));
                return $fileName;
             }
              
        }

    }


    public function CustomerBalance(Request $request)
    {
        $warehouse = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('Accumulated (All)', '');
        return view('customer-reports.supplier.balance', compact('warehouse'));
    }

    public function CustomerAging(Request $request)
    {
        $customers = Party::orderBy('party_name')->pluck('party_name', 'id')->prepend('Select Customer', '');
        return view('customer-reports.supplier.aging', compact('customers'));
    }
}
