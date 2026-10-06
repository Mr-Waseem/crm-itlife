<?php

namespace App\Http\Controllers;
use App\Models\GeneralVoucher;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use App\Models\Warehouse;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;
use Illuminate\Support\Facades\Response;
class GeneralJournalController extends Controller
{
    public function index(Request $request){
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'GENERAL JOURNAL')
                ->where('right_name', 'PRINT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

        if ($request->ajax()) {
            // return "d";
            // return $request;
            $fromDate = $request->from_date;
           $toDate = $request->to_date;
           $report_type = $request->report_type;
           $voucher_type = $request->voucher_type;
           $warehouseID = $request->warehouseID;

          $OpeningcustomerLedger = DB::table('general_vouchers')
                   ->select(DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                   ->whereDate('date', '<', $fromDate)
                   // ->whereDate('date', '<=', $toDate)
                //    ->where('account_head_id', $customer_id)
                   ->first();

           if ($report_type == 'summary') {
            //    return "sum";
                 $customerLedger = DB::table('general_vouchers')
                   //->join('account_groups', 'account_groups.id', '=', 'general_vouchers.account_head_id')
                    ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                    ->join('warehouses', 'warehouses.id', '=', 'general_vouchers.warehouse_id')
                   ->select('voucher_no', 'v_type','warehouses.name','parties.code', 'parties.party_name', 'date', 'narration', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
                //    ->select('voucher_no', 'v_type', 'parties.party_name', 'date', 'narration', 'debit', 'credit')
                       ->where('v_type', '!=', 'Opening Balance')
                    ->groupBy('account_head_id')
                    ->groupBy('voucher_id')
                   
                //    ->groupBy('account_head_id')
                //    ->where('v_type', 'SALE') Opening Balance
                   ->where(function ($query) use ($voucher_type) {
                    if ($voucher_type != 0) {
                        $query->where('general_vouchers.v_type', $voucher_type);
                        }
                    })
                    ->where(function ($query) use ($warehouseID) {
                        if ($warehouseID != 0) {
                            $query->where('general_vouchers.warehouse_id', $warehouseID);
                            }
                        })
                    
                   ->whereDate('date', '>=', $fromDate)
                   ->whereDate('date', '<=', $toDate)
                //    ->where('account_head_id', $customer_id)
                //    ->groupBy('general_vouchers.voucher_id')
                   ->orderBy('date', 'asc')
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
            // return "d";
               $customerLedger = GeneralVoucher::with('other_parties')->with('products')
                   ->whereDate('date', '>=', $fromDate)
                   ->whereDate('date', '<=', $toDate)
                //    ->where('account_head_id', $customer_id)
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

        $vouchertype = ['0' => 'All', 'Cash Receipt' => 'CASH RECEIPTS', 'Cash Payment' => 'CASH PAYMENTS', 'Bank Receipt' => 'BANK RECEIPTS', 'Bank Payment' => 'BANK PAYMENTS', 'Journal Voucher' => 'JOURNAL VOUCHERS',  'PURCHASE' => 'PURCHASE', 'PURCHASE RETURN' => 'PURCHASE RETURN', 'SALE' => 'SALE', 'SALE RETURN' => 'SALE RETURN', 'ISSUANCE' => 'ISSUANCE', 'ISSUANCE RETURN' => 'ISSUANCE RETURN'];
         $warehouse = Warehouse::OrderBy('id', 'asc')->pluck('name', 'id')->prepend('All Warehouses', '0');
        $SingleWarehouse = Warehouse::where('id', Auth::User()->warehouse_id)->pluck('name', 'id');
        return view('financial-reports.general-journal.index', Compact('vouchertype', 'warehouse', 'SingleWarehouse'));
    }


    public function report(Request $request){
        // $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        //         ->where('voucher_name', 'GENERAL JOURNAL')
        //         ->where('right_name', 'PRINT')
        //         ->first();
        //     if (!$voucherRight) {
        //         return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        //     }
        $fromDate = $request->from_date;
           $toDate = $request->to_date;
           $report_type = $request->report_type;
           $voucher_type = $request->voucher_type;
           $warehouseID = $request->warehouseID;
        $customerLedger = DB::table('general_vouchers')
        //->join('account_groups', 'account_groups.id', '=', 'general_vouchers.account_head_id')
         ->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
         ->join('warehouses', 'warehouses.id', '=', 'general_vouchers.warehouse_id')
        ->select('voucher_no', 'v_type','warehouses.name','parties.code', 'parties.party_name', 'date', 'narration', DB::raw('SUM(debit) as debit'), DB::raw('SUM(credit) as credit'))
     //    ->select('voucher_no', 'v_type', 'parties.party_name', 'date', 'narration', 'debit', 'credit')
            ->where('v_type', '!=', 'Opening Balance')
         ->groupBy('account_head_id')
         ->groupBy('voucher_id')
        
     //    ->groupBy('account_head_id')
     //    ->where('v_type', 'SALE') Opening Balance
        ->where(function ($query) use ($voucher_type) {
         if ($voucher_type != 0) {
             $query->where('general_vouchers.v_type', $voucher_type);
             }
         })
         ->where(function ($query) use ($warehouseID) {
            if ($warehouseID != 0) {
                $query->where('general_vouchers.warehouse_id', $warehouseID);
                }
            })
        ->whereDate('date', '>=', $fromDate)
        ->whereDate('date', '<=', $toDate)
     //    ->where('account_head_id', $customer_id)
     //    ->groupBy('general_vouchers.voucher_id')
        ->orderBy('date', 'asc')
        ->orderBy('general_vouchers.id', 'asc')
        // ->whereBetween('sale_details.created_at', [$fromDate, $toDate])
        ->get()->toArray();
        $pdf = PDF::loadView('financial-reports.general-journal.print', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
        $fileName =  'General-Journal.pdf';
        $pdf->save(base_path('upload/ledger/' . $fileName));
        return $fileName;
            }
}
