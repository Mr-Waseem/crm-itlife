<?php

namespace App\Http\Controllers;

use App\Models\Party;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use PDF;

class SalarySheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(){
        $data['employee'] = Party::
            select(DB::raw(
            'CONCAT(`id`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->where('role','Employee')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('All Employees', '0');
        return view('salary-sheet-report.index',$data);
    }
    public function SalarysheetPDF(Request $request){
        // return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'SALARY SHEET REPORT')
        ->where('right_name', 'PRINT')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        $fromDate = $request->from_date;
         $date = Carbon::parse($request->from_date);
        $monthName = $date->format('m');
        $YearName = $date->format('Y');
        $employee_id = $request->Employee_id;
        if($employee_id==0){
            //   $employee = DB::table('parties as p')
            // ->join('attendances', 'p.id', '=', 'attendances.employee_id')
            // ->join('designations', 'designations.id', '=', 'p.designation_id')
            // ->join('general_vouchers', 'general_vouchers.advance_employee_id', '=', 'p.id')
            // ->select(
            //     'p.*',
            //     'attendances.*',
            //     'designations.title',
            //     DB::raw('SUM(attendances.over_time) as totalOverTime'),
            //     DB::raw('SUM(CASE WHEN general_vouchers.advance_types = "ADVANCE" THEN general_vouchers.debit ELSE 0 END) as totalAdvance'),
            //     DB::raw('SUM(CASE WHEN general_vouchers.advance_types = "CANTEEN" THEN general_vouchers.debit ELSE 0 END) as totalcanteen'),
            //     DB::raw('SUM(CASE WHEN general_vouchers.advance_types = "LOAN" THEN general_vouchers.debit ELSE 0 END) as totalloan'),
            //     DB::raw('SUM(CASE WHEN general_vouchers.advance_types = "PENALTY" THEN general_vouchers.debit ELSE 0 END) as totalpenalty'),
            // )
            // ->selectRaw("COUNT(IF(attendances.status = 0, 1, NULL)) as absent_count")
            // ->selectRaw("COUNT(IF(attendances.status = 1, 1, NULL)) as present_count")
            // ->selectRaw("COUNT(IF(attendances.status = 2, 1, NULL)) as leave_count")
            // // ->whereDate('attendances.date', '=', $fromDate)
            // ->whereMonth('attendances.date', $monthName)
            // ->whereYear('attendances.date', $YearName)
            // // ->where('p.id', $employee_id)
            // ->groupBy('p.id', 'attendances.employee_id')
            // ->orderBy('p.party_name', 'asc')
            // ->get();
              $employee = Party::with('designation:id,title')->with(['attendance' => function($query){
                $query->count('time_in');
            }])
            // ->with(['employees_advance' => function($query){
            //     // $query->withCount([
            //     // $query->where('debit', '>', 0);
            //     // // $query->select(DB::raw('sum(case when advance_types = "LOAN as DATE" then debit end) as openingDebit'));
            //     // $query->select(DB::raw('sum(debit) as openingDebit'));
            //     // ]);
            // }])
            ->withCount([
                'employees_advance AS Total_Loan' => function ($query) use ($monthName, $YearName) {
                    $query->where('debit', '>', 0);
                    $query->select(DB::raw('sum(case when advance_types = "LOAN" && MONTH(date) = "'.$monthName.'" && YEAR(date) = "'.$YearName.'" then debit end)'));
                },
                'employees_advance AS Total_Advance' => function ($query) use ($monthName, $YearName) {
                    $query->where('debit', '>', 0);
                    $query->select(DB::raw('sum(case when advance_types = "ADVANCE" && MONTH(date) = "'.$monthName.'" && YEAR(date) = "'.$YearName.'" then debit end)'));
                },
                'employees_advance AS Total_Canteen' => function ($query) use ($monthName, $YearName) {
                    $query->where('debit', '>', 0);
                    $query->select(DB::raw('sum(case when advance_types = "CANTEEN" && MONTH(date) = "'.$monthName.'" && YEAR(date) = "'.$YearName.'" then debit end)'));
                },
                'employees_advance AS Total_Penalty' => function ($query) use ($monthName, $YearName) {
                    $query->where('debit', '>', 0);
                    $query->select(DB::raw('sum(case when advance_types = "PENALTY" && MONTH(date) = "'.$monthName.'" && YEAR(date) = "'.$YearName.'" then debit end)'));
                }
            ])
            ->where('account_type', 'EMPLOYEE')
            ->where('employee_status', 1)
            ->orderBy('party_name', 'asc')
            ->get();
        
            $pdf = PDF::loadView('salary-sheet-report.salary-summary-print', compact('employee','fromDate'))->setPaper('a4', 'landscape');
            $fileName =  'Summary-Salary-Sheet-Report.pdf';
            $pdf->save(base_path('upload/salary-sheet-report/' . $fileName));
            return $fileName;
        }else{
            // return "D";
                $carbonDate = Carbon::parse($fromDate);
                $monthNumber = $carbonDate->month;
                 $YearNumber = $carbonDate->year;
                 $employee = Party::with(['warehouse','designation','attendance' => function($query) use ($monthNumber, $YearNumber) {
                        $query->whereRaw('MONTH(date) = ?', [$monthNumber]);
                        $query->whereRaw('YEAR(date) = ?', [$YearNumber]);
                        $query->orderBy('date', 'desc');
                    }
                    
                ])
                ->with(['employeehistory' =>function($query) use ($monthNumber, $YearNumber){
                    $query->whereRaw('MONTH(created_at) = ?', [$monthNumber]);
                    $query->whereRaw('YEAR(created_at) = ?', [$YearNumber]);
                    $query->OrderBy('id', 'desc')->first();
                }])
                ->with(['employees_advance' => function($query){
                    $query->where('debit', '>', 0);
                }])
                ->where('id', $employee_id)->first();
        //         $monthName = $date->format('m');
        // $YearName = $date->format('Y');
            $Monthdays = Carbon::createFromDate($YearName, $monthName)->daysInMonth;
            $pdf = PDF::loadView('salary-sheet-report.salary-detail-print', compact('employee','fromDate', 'Monthdays'))->setPaper('a4', 'landscape');
            $fileName =  'detail-Salary-Sheet-Report.pdf';
            $pdf->save(base_path('upload/salary-sheet-report/' . $fileName));
            return $fileName;
        }
        
        // }
        // if ($report_type == 'detailed') {
        //         $customerLedger = GeneralVoucher::with('other_parties')->with('products')->with('warehouse')
        //         ->where(function ($query) use ($warehouseID) {
        //             if ($warehouseID != 0) {
        //                 $query->where('warehouse_id', $warehouseID);
        //                 }
        //             })
        //         ->whereDate('date', '=', $fromDate)
        //         // ->whereDate('date', '<=', $toDate)
        //         ->where('account_head_id', $customer_id)
        //         ->orderBy('date', 'asc')
        //         ->orderBy('id', 'asc')
        //         ->get();

        //         $pdf = PDF::loadView('customer-reports.cash-book-single.detail-print', compact('customerLedger', 'OpeningcustomerLedger', 'customer', 'fromDate'));
        //         $fileName =  'Cash-Book-Detail.pdf';
        //         $pdf->save(base_path('upload/ledger/' . $fileName));
        //         return $fileName;
               
        // }

    }
}
