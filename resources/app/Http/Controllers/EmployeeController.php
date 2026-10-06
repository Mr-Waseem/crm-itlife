<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Party;
use App\Models\Warehouse;
use App\Models\EmployeeHistory;
use App\Models\Designation;
use App\Models\EmployeeType;
use Illuminate\Http\Request;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Response;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Carbon\Carbon;
use Illuminate\Support\Str;
class EmployeeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index(Request $request)
    {
        $code = Party::whereRole('Employee')->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = (int)$code->code + 1;
        }
        if ($request->ajax()) {
                $party = Party::with('user')->with('warehouse')
               ->with(['employeehistory' => function($query){
                $query->OrderBy('id', 'desc')->first();
            }])
            ->whereRole('Employee')->OrderBy('id', 'asc')->get();
            return DataTables::of($party)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                                    <button type="button" name="' . $row->id . '_' . $row->party_name . '_' . 
                                    $row->address . '_' . $row->phone . '_' . $row->employee_id . '_' . 
                                    $row->spous_of . '_' . $row->cnic_no . '_' . $row->status . '_' . 
                                    $row->blood_relative_mbl . '_' . $row->relationship . '_' . 
                                    $row->joining_date . '_' . $row->shop_id . '_' . $row->employeehistory->monthly_salary . '_' . 
                                    $row->employeehistory->basic_salary . '_' . $row->employeehistory->house_rent . '_' . 
                                    $row->employeehistory->medical_allowance . '_' . $row->employeehistory->attendance_allowance . '_' . 
                                    $row->employeehistory->paid_leaves . '_' . $row->employeehistory->travelling_allowance . '_' . 
                                    $row->referred_by_emp_no . '_' . $row->referred_cnic . '_' . 
                                    $row->code . '_' . $row->personal_mbl_no . '_' . $row->designation_id . '_' . 
                                    $row->employee_type_id . '_' . $row->user->email . '_' . $row->working_hours . '_' . 
                                    $row->employeehistory->employee_status .'_' . $row->employeehistory->over_time .'_' . 
                                    $row->employeehistory->mobile_allowance .'_' . $row->employeehistory->eidi .'_' . 
                                    $row->employeehistory->other_allowance .'_' . $row->employeehistory->bonus_type .'_' . $row->employeehistory->working_hours 
                                    .'_' . $row->employeehistory->over_time .'_' . $row->employeehistory->employee_status . '" class="btn btn-primary edit_btn btn-sm"><i class="fa fa-pencil"></i></button>&nbsp;
                                    <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>&nbsp;
                                    <button href="javascript:void(0)" name="' . $row->id . '" class="btn btn-info print_btn btn-sm"><i class="fa fa-user"></i></button>
                                </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $dept = Warehouse::orderBy('name')->pluck('name', 'id')->prepend('Select Warehouse', '');
        $employee = Party::whereRole('Employee')->select(DB::raw('`id`, CONCAT(`code`,"-",`party_name`) as `employee`'))->pluck('employee', 'id')->prepend("Select Employee", "");
        $designations = Designation::orderBy('id')->pluck('title','id')->prepend('Select Designation','');
        $emp_types = EmployeeType::orderBy('id')->pluck('title','id')->prepend('Select Employee Type','');
        
         $parties = Party::with('user')->with('warehouse')
        ->with(['employeehistory' => function($query){
         $query->OrderBy('id', 'desc')->first();
     }])
     ->whereRole('Employee')->OrderBy('id', 'asc')->get();

        return view('employees.index', compact('codes', 'dept', 'employee','designations','emp_types', 'parties'));
    }

    public function EditEmployee(Request $request){
        // return $request;
        return $emp = Party::with('user')
        ->with(['employeehistory' => function($query){
            $query->OrderBy('id', 'desc')->first();
        }])
        ->where('id', $request->EmployeeID)->first();
    }

    public function store(Request $request)
    {
        // return $request;
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ADD EMPLOYEES')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

             $data = $request->all();
            // $data['created_by'] = Auth::User()->id;
            $data['updated_by'] = Auth::User()->id;
            $data['employee_id'] = $request->idd;

            $party = Party::find($request->idd);
            // return $request;
            $history = EmployeeHistory::where('employee_id', $party->id)
          ->OrderBy('id', 'desc')
          ->first();
          if($history->monthly_salary != $request->monthly_salary || 
          $history->basic_salary != $request->basic_salary || 
          $history->house_rent != $request->house_rent || 
          $history->medical_allowance != $request->medical_allowance ||
          $history->attendance_allowance != $request->attendance_allowance ||
          $history->paid_leaves != $request->paid_leaves ||
          $history->travelling_allowance != $request->travelling_allowance ||
          $history->mobile_allowance != $request->mobile_allowance ||
          $history->eidi != $request->eidi ||
          $history->other_allowance != $request->other_allowance ||
          $history->bonus_type != $request->bonus_type ||
          $history->working_hours != $request->working_hours ||
          $history->over_time != $request->over_time ||
          $history->employee_status != $request->employee_status
          ){
            EmployeeHistory::create($data);
          }
            
            // return "d";
            $party->update($data);
            // return $startDate = Carbon::parse($request->time_in);
            // $endDate = Carbon::parse($request->time_out);

            // // Calculate the duration
            // return $duration = $endDate->diff($startDate);

            // $startTime = Carbon::createFromFormat('H:i', $request->time_in);
            // $endTime = Carbon::createFromFormat('H:i', $request->time_out);
        
            // // Calculate the duration
            // return $duration = $endTime->diff($startTime);
            if ($request->hasFile('cnic_front_img')) {
                $file = $request->file('cnic_front_img');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->cnic_front_img = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('cnic_back_img')) {
                $file = $request->file('cnic_back_img');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->cnic_back_img = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('current_picture')) {
                $file = $request->file('current_picture');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->current_picture = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('police_report')) {
                $file = $request->file('police_report');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->police_report = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('academic_file')) {
                $file = $request->file('academic_file');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->academic_file = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->signature = 'root/upload/employee/' . $fileName;
                $party->save();
            }

            return redirect()->back()->with('flash_message', 'Employee Updated Successfully!');
        } else {
            $this->validate($request, [
                'party_name' => 'required',
                'phone' => 'required',
                // 'personal_mbl_no' => 'required',
                'address' => 'required',
                'shop_id' => 'required',
                'monthly_salary' => 'required',
                // 'time_in' => 'required',
                // 'time_out' => 'required',
                'working_hours' => 'required',
                'employee_status' => 'required',
                // 'email' => 'required|unique:users,email',
                'designation_id'=>'required|exists:designations,id',
                'employee_type_id'=>'required|exists:employee_types,id'
            ]);
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ADD EMPLOYEES')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }
            // return $request;
            //   $code = Party::whereRole('Employee')
            // ->where('shop_id', $request->shop_id)
            // ->orderBy('id', 'desc')->first();
            // $codes = 1;
            // if ($code) {
            //     $codes = (int)$code->code + 1;
            // }
            // return $codes;
            $email = Str::slug($request->input('party_name'));
            $uniqueEmail = $email.$request->code."@gmail.com";

            $data = $request->all();
            // $data['code'] = $codes;
            // $data['email'] = $uniqueEmail;
            // $data['password'] = bcrypt($uniqueEmail);
            $data['role'] = 'Employee';
            $data['account_type'] = 'EMPLOYEE';
            $data['created_by'] = Auth::User()->id;
            $party = Party::create($data);
             $data['employee_id'] = $party->id;
            EmployeeHistory::create($data);
            // return "dd";

                $user = new User();
                $user->name = $request->party_name;
                $user->address = $request->address;
                $user->email = $uniqueEmail;
                $user->password = bcrypt($uniqueEmail);
                $user->showpassword = $uniqueEmail;
                $user->phone = $request->phone;
                $user->party_id = $party->id;
                $user->status = $request->status;
                $user->role = 'Employee';
                $user->warehouse_id = Auth::User()->warehouse_id;
                $user->save();
 
            if ($request->hasFile('cnic_front_img')) {
                $file = $request->file('cnic_front_img');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->cnic_front_img = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('cnic_back_img')) {
                $file = $request->file('cnic_back_img');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->cnic_back_img = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('current_picture')) {
                $file = $request->file('current_picture');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->current_picture = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('police_report')) {
                $file = $request->file('police_report');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->police_report = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('academic_file')) {
                $file = $request->file('academic_file');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->academic_file = 'root/upload/employee/' . $fileName;
                $party->save();
            }
            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                $fileExtension = $file->getClientOriginalExtension();

                $fileName = uniqid() . '.' . $fileExtension;
                $file->move('root/upload/employee', $fileName);
                $party->signature = 'root/upload/employee/' . $fileName;
                $party->save();
            }

         

            return redirect()->back()->with('flash_message', 'Employee Added Successfully!');
        }
    }
    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ADD EMPLOYEES')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }
            $party = Party::find($id);
        if ($party) {
            EmployeeHistory::where('employee_id', $id)->delete();
            Party::findOrFail($id)->delete();
            File::delete($party->current_picture);
            File::delete($party->cnic_front_img);
            File::delete($party->cnic_back_img);
            File::delete($party->police_report);
            File::delete($party->academic_file);
            User::where('party_id', $party->id)->delete();
            return redirect()->back()->with('flash_message', 'Employee has been deleted!');
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong');
        }
    }
    public function PrintEmployee(Request $request)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ADD EMPLOYEES')
            ->where('right_name', 'PRINT')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        File::cleanDirectory(base_path() . '/upload/employeeprint');
        $id = $request->id;
        if ($id) {
            $employee = Party::with('user')->where('id', $id)->first();
            $pdf = PDF::loadView('employees.print', compact('employee'));
            $fileName =  'Employeeprint' . $id . '.pdf';
            $pdf->save(base_path('upload/employeeprint/' . $fileName));
            return $fileName;
        } else {
            return false;
        }
    }
}
