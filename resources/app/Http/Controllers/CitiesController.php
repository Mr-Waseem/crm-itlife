<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;

class CitiesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $cities = Cities::OrderBy('id', 'asc')->get();

            return DataTables::of($cities)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->name . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="cities/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm d-none"><i class="fa fa-trash"></i></a>
                                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('cities.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CITIES')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $edit = Cities::find($request->idd);
            $edit->update($request->all());
            return redirect()->back()->with('flash_message', 'City Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'CITIES')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            Cities::create($request->all());
            return redirect()->back()->with('flash_message', 'City Added Successfully');
        }
        return redirect()->back()->with('error_message', 'Something went wrong!!!');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'CITIES')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        Cities::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', 'City Deleted Successfully');
    }
}