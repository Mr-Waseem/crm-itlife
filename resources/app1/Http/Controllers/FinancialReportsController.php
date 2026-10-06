<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Party;
use App\Models\Vouchers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class FinancialReportsController extends Controller
{
    public function AccountActivity(Request $request)
    {
        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $report_type = $request->report_type;
            $customer_id = $request->customer_id;
            $party = Party::find($customer_id);
            $sum = 0;


            if ($report_type == 'summary') {
                $activityReport = Vouchers::with('voucher_party:id,party_name')->whereDate('voucher_date', '>=', $fromDate)
                    ->whereDate('voucher_date', '<=', $toDate)
                    ->where('account_id', $customer_id)
                    ->whereStatus(1)
                    ->orderBy('voucher_date', 'asc')
                    ->get();

                return Response::json(['data' => $activityReport, 'party' => $party]);
            }
            if ($report_type == 'detailed') {
                $activityReport = GeneralVoucher::with('other_parties')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('account_head_id', $customer_id)
                    ->whereStatus(1)
                    ->orderBy('date', 'asc')
                    ->get();

                return Response::json(['data' => $activityReport, 'party' => $party]);
            }
        }


        $parties = Party::whereNotIn('party_name', ['PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->where('account_type', 'CUSTOMER')
            ->OrWhere('party_name', 'CASH IN HAND')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Party/Account', '');
        return view('financial-reports.activity-report', compact('parties'));
    }
}