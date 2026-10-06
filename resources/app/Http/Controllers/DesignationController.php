<?php

namespace App\Http\Controllers;

use App\Http\Requests\DesignationValidationRequest;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Party;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $designations = Designation::select('id', 'title')->get();

            return DataTables::of($designations)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->title . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-designation d-none"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('designations.index');
    }

    public function store(DesignationValidationRequest $request)
    {
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DESIGNATION')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $designation = Designation::find($request->idd);
            $designation->update($request->only('title'));

            return redirect()->back()->with('flash_message', 'Designation Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'DESIGNATION')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            Designation::create($request->only('title'));
            return redirect()->back()->with('flash_message', 'Designation Added Successfully');
        }

        return redirect()->back()->with('error_message', 'Something went wrong');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'DESIGNATION')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $designation = Designation::findOrFail($id);
        if ($designation) {
            $designation_exist = Party::where('designation_id',$id)->count();
            if($designation_exist>0)
            {
                return redirect()->back()->with('error_message', 'Employee Already Exist.');
            }

            $designation->delete();
            return redirect()->back()->with('flash_message', 'Designation Deleted Successfully');
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong');
        }
    }
}
