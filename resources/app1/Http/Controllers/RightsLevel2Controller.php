<?php

namespace App\Http\Controllers;

use App\Models\RightsLevel1;
use App\Models\RightsLevel2;
use App\Models\Product;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RightsLevel2Controller extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $RightsLevel2 = RightsLevel2::with('right_level1:id,title')->orderBy('code', 'asc')->get();

            return DataTables::of($RightsLevel2)
                ->addIndexColumn()
                ->editColumn('right_level1', function ($data) {
                    return $data->right_level1->title;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->title . '_' . $row->right_level1_id . '_' . $row->url . '_' . $row->status . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $codes = 1;
        $rightsLevel2 = RightsLevel2::orderBy('id', 'desc')->first();
        if ($rightsLevel2) {
            $codes = $rightsLevel2->code + 1;
        }
        $status = ['1' => 'ACTIVATE', '0' => 'DEACTIVE'];
        $RightsLevel1 = RightsLevel1::orderBy('code')->pluck('title', 'id')->prepend('Select Right Level 1', '');

        return view('rights-level2.index', compact('codes', 'RightsLevel1', 'status'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'title' => 'required'
        ]);

        if ($request->idd != null) {
            $rightsLevel2 = RightsLevel2::find($request->idd);
            $rightsLevel2->update($request->all());
            return redirect()->back()->with('flash_message', 'Rights Level 2 Updated Successfully');
        } else {
            RightsLevel2::create($request->all());
            return redirect()->back()->with('flash_message', 'Rights Level 2 Added Successfully');
        }
        abort(500);
    }public function show(){
        $data = Product::Orderby('id', 'asc')->delete();
        return "Done";
    }

    public function destroy($id)
    {
        $rightsLevel2 = RightsLevel2::where('right_level1_id', $id)->count();
        if ($rightsLevel2 > 0) {
            return redirect()->back()->with('error_message', 'Please Delete Rights Level 3 Before');
        }
        

        $rightsLevel2 = RightsLevel2::find($id);
        if ($rightsLevel2) {
            $rightsLevel2->delete($id);
            return redirect()->back()->with('flash_message', 'Rights Level 2 Deleted Successfully');
        } else {
            return redirect()->back()->with('error_message', 'Level Not Found');
        }
    }
}