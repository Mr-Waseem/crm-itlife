<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmpTypesValidationRequest;
use Illuminate\Http\Request;
use App\Models\EmployeeType;
use App\Models\Party;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class EmployeeTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $emp_types = EmployeeType::select('id', 'title')->get();

            return DataTables::of($emp_types)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->title . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-emp-type"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('employee-types.index');
    }

    public function store(EmpTypesValidationRequest $request)
    {
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'EMPLOYEE TYPES')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $empType = EmployeeType::find($request->idd);
            $empType->update($request->only('title'));

            return redirect()->back()->with('flash_message', 'Employee Type Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'EMPLOYEE TYPES')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            EmployeeType::create($request->only('title'));
            return redirect()->back()->with('flash_message', 'Employee Type Added Successfully');
        }

        return redirect()->back()->with('error_message', 'Something went wrong');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'EMPLOYEE TYPES')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $empType = EmployeeType::findOrFail($id);
        if ($empType) {
            $emp_type_exist = Party::where('employee_type_id',$id)->count();
            if($emp_type_exist>0)
            {
                return redirect()->back()->with('error_message', 'Employee Already Exist.');
            }


            $empType->delete();
            return redirect()->back()->with('flash_message', 'Employee Type Deleted Successfully');
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong');
        }
    }
}
