<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\Vouchers;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables;
use DB;
use PDF;
class JournalLedgerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function JournalLedger(Request $request)
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

            if ($report_type == 'summary') {
                
                 $customerLedger = DB::table('general_vouchers')
                    //->join('account_groups', 'account_groups.id', '=', 'general_vouchers.account_head_id')
                    // ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                    ->select('voucher_no', 'v_type', 'date', 'narration', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
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
                // return "ddff";
                $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    // ->whereStatus(1)
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();

                // return Response::json(['data' => $customerLedger]);
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
            }
        }


        $parties = Party::OrderBy('party_name', 'asc')
            // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
            // ->whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT', 'CASH IN HAND'])
            ->pluck('party_name', 'id')
            ->prepend('Select Account', '');
        return view('financial-reports.journal-ledger.index', compact('parties'));
    }


    public function LedgerPDF(Request $request){


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

        if ($report_type == 'summary') {
            $customerLedger = DB::table('general_vouchers')
                ->select('voucher_no', 'v_type', 'date', 'narration', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->groupBy('voucher_id')
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();

        $pdf = PDF::loadView('financial-reports.journal-ledger.summary', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate'));
        $fileName =  'Customer-ledger.pdf';
        $pdf->save(base_path('upload/ledger/' . $fileName));
        return $fileName;
        }
        if ($report_type == 'detailed') {

                $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get();

        $pdf = PDF::loadView('financial-reports.journal-ledger.detail', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate'));
        $fileName =  'Customer-ledger.pdf';
        $pdf->save(base_path('upload/ledger/' . $fileName));
        return $fileName;
        }

}
}
