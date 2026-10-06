<?php

namespace App\Http\Controllers;

use App\Models\CustomerProduct;
use App\Models\GeneralVoucher;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use DB;
use PDF;

/**
 * SPI-parity Customer Ledger as a new PEH module.
 * Does not replace existing customer-reports routes/controllers.
 * PEH general_vouchers has no dc_no column — omitted from selects.
 */
class CustomerLedgerAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $report_type = $request->report_type;
            $customer_id = $request->customer_id;

            $OpeningcustomerLedger = DB::table('general_vouchers')
                ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                ->whereDate('date', '<', $fromDate)
                ->where('account_head_id', $customer_id)
                ->first();

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

                return Response::json([
                    'data' => $customerLedger ?: '',
                    'OpeningcustomerLedger' => $OpeningcustomerLedger,
                ]);
            }

            if ($report_type == 'detailed') {
                $DeliveryChallan = CustomerProduct::where('customer_id', $customer_id)->count();
                if ($DeliveryChallan > 0) {
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
                } else {
                    $status = '1';
                    $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                        ->whereDate('date', '>=', $fromDate)
                        ->whereDate('date', '<=', $toDate)
                        ->where('account_head_id', $customer_id)
                        ->orderBy('date', 'asc')
                        ->orderBy('id', 'asc')
                        ->get();
                }

                return Response::json([
                    'data' => $customerLedger ?: '',
                    'status' => $status,
                    'OpeningcustomerLedger' => $OpeningcustomerLedger,
                ]);
            }
        }

        $parties = Party::select(DB::raw('CONCAT(`id`) AS `id`, CONCAT(`code`,"-",`party_name`) `party_name`'))
            ->OrderBy('party_name', 'asc')
            ->where('role', 'Customer')
            ->pluck('party_name', 'id')
            ->prepend('All Customer', '0');

        return view('customer-ledger-account.index', compact('parties'));
    }

    public function LedgerPDF(Request $request)
    {
        $fromDate = $request->from_date;
        $toDate = $request->to_date;
        $report_type = $request->report_type;
        $customer_id = $request->Customerid;
        $customer = Party::where('id', $customer_id)->first();
        $OpeningcustomerLedger = DB::table('general_vouchers')
            ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
            ->whereDate('date', '<', $fromDate)
            ->where('account_head_id', $customer_id)
            ->first();

        if ($report_type == 'summary') {
            if ($customer_id == 0) {
                $customerLedger = DB::table('general_vouchers')->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                    ->select(
                        'parties.code',
                        'parties.party_name',
                        DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'),
                        DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'),
                        DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'),
                        DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit')
                    )
                    ->where('parties.role', 'Customer')
                    ->groupBy('account_head_id')
                    ->orderBy('parties.party_name', 'asc')
                    ->get()->toArray();

                $pdf = PDF::loadView('customer-ledger-account.all-parties-balance', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate'));
                $fileName = 'Customer-ledger.pdf';
                $pdf->save(base_path('upload/ledger/' . $fileName));
                return $fileName;
            }

            $customerLedger = DB::table('general_vouchers')
                ->select('voucher_no', 'v_type', 'date', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                ->whereDate('date', '>=', $fromDate)
                ->whereDate('date', '<=', $toDate)
                ->where('account_head_id', $customer_id)
                ->groupBy('voucher_id')
                ->orderBy('date', 'asc')
                ->orderBy('id', 'asc')
                ->get()->toArray();

            $pdf = PDF::loadView('customer-ledger-account.summary-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate'));
            $fileName = 'Customer-ledger.pdf';
            $pdf->save(base_path('upload/ledger/' . $fileName));
            return $fileName;
        }

        if ($report_type == 'detailed') {
            if ($customer_id == 0) {
                $customerLedger = DB::table('general_vouchers')->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                    ->select(
                        'parties.code',
                        'parties.party_name',
                        DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'),
                        DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'),
                        DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'),
                        DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit')
                    )
                    ->where('parties.role', 'Customer')
                    ->groupBy('account_head_id')
                    ->orderBy('parties.party_name', 'asc')
                    ->get()->toArray();

                $pdf = PDF::loadView('customer-ledger-account.all-parties-balance', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate'));
                $fileName = 'Customer-ledger.pdf';
                $pdf->save(base_path('upload/ledger/' . $fileName));
                return $fileName;
            }

            $DeliveryChallan = CustomerProduct::where('customer_id', $customer_id)->count();
            if ($DeliveryChallan > 0) {
                $status = '0';
                $customerLedger = GeneralVoucher::with('customer_products')
                    ->with('other_parties')->with('products')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();
            } else {
                $status = '1';
                $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    ->orderBy('date', 'asc')
                    ->orderBy('id', 'asc')
                    ->get();
            }

            $pdf = PDF::loadView('customer-ledger-account.detail-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate', 'toDate', 'status'));
            $fileName = 'Customer-ledger.pdf';
            $pdf->save(base_path('upload/ledger/' . $fileName));
            return $fileName;
        }
    }
}
