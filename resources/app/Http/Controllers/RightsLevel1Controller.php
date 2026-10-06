<?php

namespace App\Http\Controllers;

use App\Models\RightsLevel1;
use App\Models\Warehouse;
use App\Models\RightsLevel2;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RightsLevel1Controller extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $RightsLevel1 = RightsLevel1::select('id', 'code', 'title', 'url')->orderBy('code', 'asc')->get();

            return DataTables::of($RightsLevel1)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->title . '_' . $row->url . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $codes = 1;
        $rightsLevel1 = RightsLevel1::orderBy('id', 'desc')->first();
        if ($rightsLevel1) {
            $codes = $rightsLevel1->code + 1;
        }

        return view('rights-level1.index', compact('codes'));
    }public function rights_level1(){
        // $data = Product::Orderby('id', 'asc')->delete();
         $wh = Warehouse::all();
        $sum = 0;
        foreach($wh as $ones){
            $sum = $sum + 1;
            $ones->id = $ones->id.$sum;
            $ones->save();
        }
        return "Done";
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'title' => 'required'
        ]);

        if ($request->idd != null) {
            $rightsLevel1 = RightsLevel1::find($request->idd);
            $rightsLevel1->update($request->all());
            return redirect()->back()->with('flash_message', 'Rights Level 1 Updated Successfully');
        } else {
            RightsLevel1::create($request->all());
            return redirect()->back()->with('flash_message', 'Rights Level 1 Added Successfully');
        }
        abort(500);
    }

    public function destroy($id)
    {
        $rightsLevel2 = RightsLevel2::where('right_level1_id', $id)->count();
        if ($rightsLevel2 > 0) {
            return redirect()->back()->with('error_message', 'Please Delete Rights Level 2 Before');
        }

        $rightsLevel1 = RightsLevel1::find($id);
        if ($rightsLevel1) {
            $rightsLevel1->delete($id);
            return redirect()->back()->with('flash_message', 'Rights Level 1 Deleted Successfully');
        } else {
            return redirect()->back()->with('error_message', 'Level Not Found');
        }
    }
}