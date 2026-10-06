<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Party;
use App\Models\Departments;
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
            $party = Party::with('user')->whereRole('Employee')->OrderBy('id', 'asc')->get();
            return DataTables::of($party)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group">
                                    <button type="button" name="' . $row->id . '_' . $row->party_name . '_' . $row->address . '_' . $row->phone . '_' . $row->employee_id . '_' . $row->spous_of . '_' . $row->cnic_no . '_' . $row->status . '_' . $row->blood_relative_mbl . '_' . $row->relationship . '_' . $row->joining_date . '_' . $row->dept . '_' . $row->monthly_salary . '_' . $row->basic_salary . '_' . $row->house_rent . '_' . $row->medical_allowance . '_' . $row->attendance_allowance . '_' . $row->paid_leaves . '_' . $row->travelling_allowance . '_' . $row->referred_by_emp_no . '_' . $row->referred_cnic . '_' . $row->code . '_' . $row->personal_mbl_no . '_' . $row->designation_id . '_' . $row->employee_type_id . '_' . $row->user->email . '" class="btn btn-primary edit_btn btn-sm"><i class="fa fa-pencil"></i></button>&nbsp;
                                    <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>&nbsp;
                                    <button href="javascript:void(0)" name="' . $row->id . '" class="btn btn-info print_btn btn-sm"><i class="fa fa-user"></i></button>
                                </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        $dept = Departments::pluck('name', 'id')->prepend('Select Deparment');
        $employee = Party::whereRole('Employee')->select(DB::raw('`id`, CONCAT(`code`,"-",`party_name`) as `employee`'))->pluck('employee', 'id')->prepend("Select Employee", "");
        $designations = Designation::orderBy('id')->pluck('title','id')->prepend('Select Designation','');
        $emp_types = EmployeeType::orderBy('id')->pluck('title','id')->prepend('Select Employee Type','');

        return view('employees.index', compact('codes', 'dept', 'employee','designations','emp_types'));
    }

    public function store(Request $request)
    {
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


            $party = Party::find($request->idd);
            $party->update($data);

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
                'email' => 'required|unique:users,email',
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
             $code = Party::whereRole('Employee')
            ->where('dept', $request->dept)
            ->orderBy('id', 'desc')->first();
            $codes = 1;
            if ($code) {
                $codes = (int)$code->code + 1;
            }
            // return $codes;
            $data = $request->all();

            $data['code'] = $codes;
            $data['role'] = 'Employee';
            $data['account_type'] = 'EMPLOYEE';
            $data['created_by'] = Auth::User()->id;
            $party = Party::create($data);

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

            $user = new User();
            $user->name = $request->party_name;
            $user->address = $request->address;
            $user->email = $request->email;
            // $user->password=bcrypt($request->password);
            $user->phone = $request->phone;
            $user->party_id = $party->id;
            $user->status = $request->status;
            $user->role = 'Employee';
            $user->warehouse_id = Auth::User()->warehouse_id;
            $user->save();

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
