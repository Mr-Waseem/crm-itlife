<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\AccountGroup;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use DB;
use PDF;
use Illuminate\Support\Facades\Response;
class TrialBalanceController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request){
        // $details = [
        //     'title' => 'New Taxi Booked!',
        //     'name' => "sammar",
           
        // ];
       
        // Mail::to('sammarforu@gmail.com')->send(new TestMail($details));

        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'TRIAL BALANCE')
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
        //    $voucher_type = $request->voucher_type;


           if ($report_type == 'summary') {
               
                    $customerLedger = DB::table('general_vouchers')->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
                   ->select(
                       'parties.code',
                       'parties.party_name',
                       DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'),
                       DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'),
                       DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'),
                       DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'),
                       // DB::raw("SUM(godown_stock_details.qty_in < $fromDate) as openingIn"),
                       // DB::raw("SUM(godown_stock_details.qty_out < $fromDate) as openingOut"),
                   )
                   ->where('v_type', '!=', 'Opening Balance')
                   ->groupBy('account_head_id') 
                //    ->whereDate('date', '>=', $fromDate)
                //    ->whereDate('date', '<=', $toDate)
                   ->orderBy('parties.code', 'asc')
                   ->get()->toArray();

               if($customerLedger){
                   // return Response::json(['data' => $customerLedger]);
                   return Response::json ([
                       'data' => $customerLedger,
                    //    'OpeningcustomerLedger' => $OpeningcustomerLedger
                   ]);
               } else {
                   // return Response::json(['data' => '']);
                   return Response::json ([
                       'data' => '',
                    //    'OpeningcustomerLedger' => $OpeningcustomerLedger
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

        $vouchertype = ['Detailed' => 'Detailed', 'Consolidated' => 'Consolidated', '4' => 'Level 4', '3' => 'Level 3', '2' => 'Level 2', '1' => 'Level 1'];
        return view('financial-reports.trial-balance.index', Compact('vouchertype'));
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
           if($voucher_type == "Detailed"){
            $customerLedger = DB::table('general_vouchers')->join('parties', 'parties.id', '=', 'general_vouchers.account_head_id')
            ->select(
                'parties.code',
                'parties.party_name',
                DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'),
                DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'),
                DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'),
                DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'),
                // DB::raw("SUM(godown_stock_details.qty_in < $fromDate) as openingIn"),
                // DB::raw("SUM(godown_stock_details.qty_out < $fromDate) as openingOut"),
            )
            ->where('v_type', '!=', 'Opening Balance')
            ->groupBy('account_head_id') 
                //    ->whereDate('date', '>=', $fromDate)
                //    ->whereDate('date', '<=', $toDate)
            ->orderBy('parties.code', 'asc')
            ->get()->toArray();
           
                    
            $pdf = PDF::loadView('financial-reports.trial-balance.print', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
            $fileName =  'Trial-Balance.pdf';
            $pdf->save(base_path('upload/ledger/' . $fileName));
            return $fileName;
        }
        if($voucher_type == "Consolidated"){
              $customerLedger = AccountGroup::with(['account_group_2' => function ($query)use ($fromDate, $toDate) {
                // $query->select('id', 'code', 'name');
                // $query->select('id', 'code', 'name');
                $query->select('account_group1_id', 'id', 'code', 'name');
                $query->OrderBy('code', 'asc');
               $query->with(['account_group_3' => function ($query1) use ($fromDate, $toDate){
                $query1->select('account_group2_id', 'id', 'code', 'name');
                $query1->OrderBy('code', 'asc');
                $query1->with(['parties' => function($query) use ($fromDate, $toDate){
                     $query->select('account_group_id3', 'id', 'code', 'party_name');
                     
                    $query->OrderBy('code', 'asc');
                    // $query->with(['general_vouchers' => function($data) use ($fromDate, $toDate){
                    //     $data->whereDate('date', '>=', $fromDate);
                    //     $data->whereDate('date', '<=', $toDate);
                    // }]);
                    $query->withCount([
                        'general_vouchers AS openingDebit' => function ($query) use ($fromDate, $toDate) {
                            // $query->select(DB::raw("SUM(debit) as debit"));
                            $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'))->where('v_type', '!=', 'Opening Balance');
                        },
                        'general_vouchers AS openingCredit' => function ($query) use ($fromDate, $toDate) {
                            $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'))->where('v_type', '!=', 'Opening Balance');
                        },
                        'general_vouchers AS debit' => function ($query) use ($fromDate, $toDate) {
                            $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'))->where('v_type', '!=', 'Opening Balance');
                        },
                        'general_vouchers AS credit' => function ($query) use ($fromDate, $toDate) {
                            $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'))->where('v_type', '!=', 'Opening Balance');
                        }
                    ]);
                }]);
                //    $query1->with('parties')->whereHas('parties',function($query2){
                //        $query2->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT']);
                //    });
               }]);
           }])
           ->select('id', 'code', 'name')
           ->OrderBy('code', 'asc')
           ->get();
      
            $pdf = PDF::loadView('financial-reports.trial-balance.consolidated', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
            $fileName =  'Trial-Balance-Consolidated.pdf';
            $pdf->save(base_path('upload/ledger/' . $fileName));
            return $fileName;
        }

        if($voucher_type == "4"){
            $customerLedger = AccountGroup::with(['account_group_2' => function ($query)use ($fromDate, $toDate) {
              // $query->select('id', 'code', 'name');
              // $query->select('id', 'code', 'name');
              $query->select('account_group1_id', 'id', 'code', 'name');
              $query->OrderBy('code', 'asc');
             $query->with(['account_group_3' => function ($query1) use ($fromDate, $toDate){
              $query1->select('account_group2_id', 'id', 'code', 'name');
              $query1->OrderBy('code', 'asc');
              $query1->with(['parties' => function($query) use ($fromDate, $toDate){
                $query->select('account_group_id3', 'id', 'code', 'party_name');
                  $query->OrderBy('code', 'asc');
                  $query->withCount([
                      'general_vouchers AS openingDebit' => function ($query) use ($fromDate, $toDate) {
                          // $query->select(DB::raw("SUM(debit) as debit"));
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'));
                        //   ->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS openingCredit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'));
                        //   ->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS debit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'));
                        //   ->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS credit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'));
                        //   ->where('v_type', '!=', 'Opening Balance');
                      }
                  ]);
              }]);
              //    $query1->with('parties')->whereHas('parties',function($query2){
              //        $query2->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT']);
              //    });
             }]);
         }])
         ->select('id', 'code', 'name')
         ->OrderBy('code', 'asc')
         ->get();
    
          $pdf = PDF::loadView('financial-reports.trial-balance.level4', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
          $fileName =  'Trial-Balance-Level4.pdf';
          $pdf->save(base_path('upload/ledger/' . $fileName));
          return $fileName;
        }

        if($voucher_type == "3"){
             $customerLedger = AccountGroup::with(['account_group_2' => function ($query)use ($fromDate, $toDate) {
              // $query->select('id', 'code', 'name');
              // $query->select('id', 'code', 'name');
              $query->select('account_group1_id', 'id', 'code', 'name');
              $query->OrderBy('code', 'asc');
             $query->with(['account_group_3' => function ($query1) use ($fromDate, $toDate){
              $query1->select('account_group2_id', 'id', 'code', 'name');
              $query1->OrderBy('code', 'asc');
              $query1->with(['parties' => function($query) use ($fromDate, $toDate){
                

                // $query->where(function ($query3)  {
                //     if ($query1->parties) {
                //         $query->where('godown_stock_details.type', $bill_type);
                //     } 
                // });

                $query->select('account_group_id3', 'id', 'code', 'party_name');
                  $query->OrderBy('code', 'asc');
                  $query->withCount([
                      'general_vouchers AS openingDebit' => function ($query) use ($fromDate, $toDate) {
                          // $query->select(DB::raw("SUM(debit) as debit"));
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS openingCredit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS debit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS credit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'))->where('v_type', '!=', 'Opening Balance');
                      }
                  ]);
              }]);
              //    $query1->with('parties')->whereHas('parties',function($query2){
              //        $query2->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT']);
              //    });
             }]);
         }])
         ->select('id', 'code', 'name')
         ->OrderBy('code', 'asc')
         ->get();
    
          $pdf = PDF::loadView('financial-reports.trial-balance.level3', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
          $fileName =  'Trial-Balance-Level3.pdf';
          $pdf->save(base_path('upload/ledger/' . $fileName));
          return $fileName;
        }

        if($voucher_type == "2"){
            $customerLedger = AccountGroup::with(['account_group_2' => function ($query)use ($fromDate, $toDate) {
              // $query->select('id', 'code', 'name');
              // $query->select('id', 'code', 'name');
              $query->select('account_group1_id', 'id', 'code', 'name');
              $query->OrderBy('code', 'asc');
             $query->with(['account_group_3' => function ($query1) use ($fromDate, $toDate){
              $query1->select('account_group2_id', 'id', 'code', 'name');
              $query1->OrderBy('code', 'asc');
              
              $query1->with(['parties' => function($query) use ($fromDate, $toDate){
                $query->select('account_group_id3', 'id', 'code', 'party_name');
                  $query->OrderBy('code', 'asc');
                  
                  $query->withCount([
                      'general_vouchers AS openingDebit' => function ($query) use ($fromDate, $toDate) {
                          // $query->select(DB::raw("SUM(debit) as debit"));
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS openingCredit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS debit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS credit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'))->where('v_type', '!=', 'Opening Balance');
                      }
                      
                  ]);
                  
              }]);
              //    $query1->with('parties')->whereHas('parties',function($query2){
              //        $query2->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT']);
              //    });
             }]);
         }])
         ->select('id', 'code', 'name')
         ->OrderBy('code', 'asc')
         ->get();
    
          $pdf = PDF::loadView('financial-reports.trial-balance.level2', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
          $fileName =  'Trial-Balance-Level2.pdf';
          $pdf->save(base_path('upload/ledger/' . $fileName));
          return $fileName;
        }

        if($voucher_type == "1"){
            $customerLedger = AccountGroup::with(['account_group_2' => function ($query)use ($fromDate, $toDate) {
              // $query->select('id', 'code', 'name');
              // $query->select('id', 'code', 'name');
              $query->select('account_group1_id', 'id', 'code', 'name');
              $query->OrderBy('code', 'asc');
             $query->with(['account_group_3' => function ($query1) use ($fromDate, $toDate){
              $query1->select('account_group2_id', 'id', 'code', 'name');
              $query1->OrderBy('code', 'asc');
              $query1->with(['parties' => function($query) use ($fromDate, $toDate){
                $query->select('account_group_id3', 'id', 'code', 'party_name');
                  $query->OrderBy('code', 'asc');
                  $query->withCount([
                      'general_vouchers AS openingDebit' => function ($query) use ($fromDate, $toDate) {
                          // $query->select(DB::raw("SUM(debit) as debit"));
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.debit end) as openingDebit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS openingCredit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date < "'.$fromDate.' as DATE" then general_vouchers.credit end) as openingCredit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS debit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.debit end) as debit'))->where('v_type', '!=', 'Opening Balance');
                      },
                      'general_vouchers AS credit' => function ($query) use ($fromDate, $toDate) {
                          $query->select(DB::raw('sum(case when general_vouchers.date >= "'.$fromDate.' as DATE" AND general_vouchers.date <= "'.$toDate.' as DATE" then general_vouchers.credit end) as credit'))->where('v_type', '!=', 'Opening Balance');
                      }
                  ]);
              }]);
              //    $query1->with('parties')->whereHas('parties',function($query2){
              //        $query2->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT']);
              //    });
             }]);
         }])
         ->select('id', 'code', 'name')
         ->OrderBy('code', 'asc')
         ->get();
    
          $pdf = PDF::loadView('financial-reports.trial-balance.level1', compact('customerLedger', 'fromDate', 'toDate'))->setPaper('a4', 'landscape');
          $fileName =  'Trial-Balance-Level1.pdf';
          $pdf->save(base_path('upload/ledger/' . $fileName));
          return $fileName;
        }
    }
}
