<?php

namespace App\Http\Controllers;

use App\Models\InwardGatePass;
use App\Models\Party;
use App\Models\RequestGenerateDetails;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class PurchaserReportController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function Report(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PURCHASER REPORT')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        if ($request->ajax()) {
            $fromDate = $request->from_date;
            $toDate = $request->to_date;
            $report_type = $request->report_type;
            $purchaser_id = $request->purchaser_id;
            $party = Party::find($purchaser_id);
            $sum = 0;


            if ($purchaser_id == 0) {
                $report = Party::join('request_generate_details', 'request_generate_details.purchaser_id', '=', 'parties.id')
                    ->select('party_name', DB::raw('SUM(request_generate_details.qty) as qty_in'), DB::raw('SUM(request_generate_details.provided_qty) as qty_out'))
                    ->whereDate('request_generate_details.date', '>=', $fromDate)
                    ->whereDate('request_generate_details.date', '<=', $toDate)
                    ->groupBy('request_generate_details.purchaser_id')
                    ->get();

                return Response::json(['data' => $report, 'party' => $party]);
            } else {
                $report = RequestGenerateDetails::with('supplier', 'purchaser', 'product:id,product_name,uom')
                    ->whereDate('date', '>=', $fromDate)
                    ->whereDate('date', '<=', $toDate)
                    ->where('purchaser_id', $purchaser_id)
                    ->orderBy('date', 'asc')
                    ->get();

                return Response::json(['data' => $report, 'party' => $party]);
            }
        }

        $purchasers = Party::whereRole('Purchaser')->pluck('party_name', 'id')->prepend('All', 0);
        return view('purchasers.report', compact('purchasers'));
    }
}