<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountGroup;
use App\Models\AccountGroup2;
use App\Models\MenuRights;
use App\Models\VoucherRights;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class AccountGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $groups = AccountGroup::select('id', 'code', 'name')->get();

            return DataTables::of($groups)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->name . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $code = AccountGroup::OrderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->code + 1;
        }

        return view('account-group.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'name' => 'required'
        ]);
        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ACCOUNT GROUP 1')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $group = AccountGroup::find($request->idd);
            $group->update($request->all());
            return redirect()->back()->with('flash_message', 'AG Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'ACCOUNT GROUP 1')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            AccountGroup::create($request->all());
            return redirect()->back()->with('flash_message', 'Account Group Added Successfully');
        }

        return redirect()->back()->with('error_message', 'Something went wrong');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'ACCOUNT GROUP 1')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        $ag2 = AccountGroup2::where('account_group1_id', $id)->count();
        if ($ag2 > 0) {
            return redirect()->back()->with('error_message', 'Please Delete Account Group 2 Before...');
        } else {
            AccountGroup::findOrFail($id)->delete();
            return redirect()->back()->with('flash_message', 'Account Group Deleted Successfully');
        }
    }
}