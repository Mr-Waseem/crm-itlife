<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\VoucherRights;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use DB;
class AttendanceController extends Controller
{
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

        return view('attendence.index', $data) ;
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'employee_id' => 'required|exists:parties,id',
            'date' => 'required',
            // 'over_time'=>'required',
            'status'=>'required'
        ]);
        $data = $request->all();
        $data['created_by'] = Auth::User()->id;
        $addAttendce = Attendance::create($data);
        if($addAttendce){
            return redirect()->back()->with('flash_message', 'Employee Attendce Added Successfully!');
        }else{
            return redirect()->back()->with('flash_message', 'Employee Attendce Not Added !');
        }
        
    }
    public function getEmployeeAttendence(Request $request){

    $carbonDate = Carbon::parse($request->date);
    $monthName = $carbonDate->format('F');
    $monthNumber = $carbonDate->month;

      $getAttendence = Attendance::with('get_partyname')
      ->orderby('date', 'desc')
      ->orderby('id', 'desc')
      ->where('employee_id',$request->employee_id)
      ->whereRaw('MONTH(date) = ?', [$monthNumber])
      ->get();
        if ($getAttendence) {
                return Response::json(['data' => $getAttendence]);

        } else {
            return Response::json(['data' => '']);
        }
    }
    public function update_attendce(Request $request)
    {
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
             $store->over_time = $request->over_time[$i];
             $store->status = $request->status[$i];
             $store->created_by =Auth::User()->id;
             $store->updated_by =Auth::User()->id;
             $store->save();
        }
        return redirect()->back()->with('flash_message', 'Employee Attendance Updated Successfully!');
    }
}
