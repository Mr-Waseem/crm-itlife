<?php

namespace App\Http\Controllers;

use DB;
use Carbon\Carbon;
use App\Models\Party;
use App\Models\Warehouse;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
   public function create(){
      $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'EMPLOYEES ATTENDANCE')
        ->where('right_name', 'ADD')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        $data['employee']= Party::where('role','Employee')
        ->pluck('party_name', 'id')->prepend('Select Employee', '');


        $data['employee'] = Party::
        // select(DB::raw('CONCAT(`id`, "_", `party_name`) AS `id`, `party_name`'))
            select(DB::raw(
            'CONCAT(`id`) AS `id`,
            CONCAT(`code`, "-", `party_name`) AS `party_name`'
            ))
            ->where('role','Employee')
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Select Employee', '');
             $warehouse= Warehouse::pluck('name', 'id')->prepend('Select Warehouse', '');
            $status = [ 'Present' => 'Present', 'Absent' => 'Absent', 'Leave' => 'Leave', 'Allowed' => 'Allowed', 'Special' => 'Special'];
        // return view('attendence.index', $data, $status);
        return view('attendence.index', Compact('warehouse', 'status'));
    }
    public function store(Request $request)
    {
        // return $request;
       
        $this->validate($request, [
            'employee_id' => 'required|exists:parties,id',
            'shop_id' => 'required',
            'date' => 'required',
            // 'time_in'=>'required',
            // 'time_out'=>'required',
            'status'=>'required',
            // 'extra_production'=>'required',
        ]);
        // $data = $request->all();
        // $data['created_by'] = Auth::User()->id;
        // $addAttendce = Attendance::create($data);
        $count = count($request->employee_id);
        for ($i = 0; $i < $count; $i++) {
             $store=new Attendance();
             $store->date = $request->date[$i];
             $store->employee_id = $request->employee_id[$i];
             $store->shop_id = $request->shop_id[$i];
             $store->time_in = $request->time_in[$i];
             $store->time_out = $request->time_out[$i];
             $store->working_hours = $request->working_hours[$i] ?? 0;
             $store->over_time = $request->over_time[$i];
             $store->extra_production = $request->extra_production[$i];
             $store->status = $request->status[$i];
             $store->created_by =Auth::User()->id;
             $store->updated_by =Auth::User()->id;
             $store->save();
        }
        return redirect()->back()->with('flash_message', 'Employee Attendce Added Successfully!');
        // if($addAttendce){
        //     return redirect()->back()->with('flash_message', 'Employee Attendce Added Successfully!');
        // }else{
        //     return redirect()->back()->with('flash_message', 'Employee Attendce Not Added !');
        // }
        
    }
    public function getEmployeeAttendence(Request $request){
// return $request;
        $carbonDate = Carbon::parse($request->date1);
        $monthName = $carbonDate->format('F');
        $monthNumber = $carbonDate->month;
        $YearNumber = $carbonDate->year;

        $getAttendence = Attendance::with('get_partyname:id,party_name','warehouse:id,name')
      ->orderby('date', 'desc')
      ->orderby('id', 'desc')
      ->where('employee_id',$request->employee_id)
      ->whereRaw('MONTH(date) = ?', [$monthNumber])
      ->whereRaw('YEAR(date) = ?', [$YearNumber])
      ->get();

        $employee = Party::where('id', $request->employee_id)->get(['working_hours', 'over_time']);
        // if ($getAttendence) {
                return Response::json([
                    'data' => $getAttendence,
                    'employee' => $employee
                
                ]);

        // } else {
        //     return Response::json(['data' => '']);
        // }
    }
    public function update_attendce(Request $request)
    {
        // return $request;
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
        ->where('voucher_name', 'EMPLOYEES ATTENDANCE')
        ->where('right_name', 'EDIT')
        ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
        Attendance::where('employee_id',$request->employee_id)->delete();

        $count = count($request->employee_id);
        for ($i = 0; $i < $count; $i++) {
             $store=new Attendance();
             $store->date = $request->date[$i];
             $store->employee_id = $request->employee_id[$i];
             $store->shop_id = $request->shop_id[$i];
             $store->time_in = $request->time_in[$i];
             $store->time_out = $request->time_out[$i];
             $store->working_hours = $request->working_hours[$i] ?? 0;
             $store->over_time = $request->over_time[$i];
             $store->extra_production = $request->extra_production[$i];
             $store->status = $request->status[$i];
             $store->created_by =Auth::User()->id;
             $store->updated_by =Auth::User()->id;
             $store->save();
        }
        return redirect()->back()->with('flash_message', 'Employee Attendance Updated Successfully!');
    }
    public function get_dept_employee(Request $request){
         $getemployee= Party::where('shop_id',$request->shop_id)
        ->where('role','Employee')
        ->get();
        if ($getemployee) {
            return Response::json(['data' => $getemployee]);

    } else {
        return Response::json(['data' => '']);
    }
    }
}
