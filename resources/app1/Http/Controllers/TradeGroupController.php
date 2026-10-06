<?php

namespace App\Http\Controllers;

use App\Models\Cities;
use App\Models\TradeGroup;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as DataTables;
use Brian2694\Toastr\Facades\Toastr;

class TradeGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tradeGroup = TradeGroup::OrderBy('id', 'asc')->get();

            return DataTables::of($tradeGroup)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->name . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="trade-group/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm d-none"><i class="fa fa-trash"></i></a>
                                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('trade-group.index');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ], [
            'name.required' => 'The Trade Group Name field is required.'
        ]);

        if ($request->idd != null) {
            $edit = TradeGroup::find($request->idd);
            $edit->update($request->all());
            return redirect()->back()->with(Toastr::info('Trade Group Updated Successfully'));
        } else {
            TradeGroup::create($request->all());
            return redirect()->back()->with(Toastr::info('Trade Group Added Successfully'));
        }
        return redirect()->back()->with(Toastr::info('Something went wrong!!!'));
    }

    public function destroy($id)
    {
        TradeGroup::findOrFail($id)->delete();
        return redirect()->back()->with(Toastr::info('Trade Group Deleted Successfully'));
    }
}