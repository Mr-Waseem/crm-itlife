<?php

namespace App\Http\Controllers;

use App\Models\RightsLevel2;
use App\Models\Warehouse;
use App\Models\RightsLevel3;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RightsLevel3Controller extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $RightsLevel3 = RightsLevel3::with('right_level2:id,title')->orderBy('code', 'asc')->get();

            return DataTables::of($RightsLevel3)
                ->addIndexColumn()
                ->editColumn('right_level2', function ($data) {
                    return $data->right_level2->title;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->title . '_' . $row->right_level2_id . '_' . $row->url . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-account"><i class="fa fa-trash"></i></a>
                                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $codes = 1;
        $rightsLevel3 = RightsLevel3::orderBy('id', 'desc')->first();
        if ($rightsLevel3) {
            $codes = $rightsLevel3->code + 1;
        }
        $RightsLevel2 = RightsLevel2::orderBy('code')->pluck('title', 'id')->prepend('Select Right Level 2', '');

        return view('rights-level3.index', compact('codes', 'RightsLevel2'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'title' => 'required'
        ]);

        if ($request->idd != null) {
            $rightsLevel3 = RightsLevel3::find($request->idd);
            $rightsLevel3->update($request->all());
            return redirect()->back()->with('flash_message', 'Rights Level 3 Updated Successfully');
        } else {
            RightsLevel3::create($request->all());
            return redirect()->back()->with('flash_message', 'Rights Level 3 Added Successfully');
        }
        abort(500);
    }public function rights_level3(){
        // $data = Product::Orderby('id', 'asc')->delete();
          $wh = Warehouse::all();
        $sum = 0;
        foreach($wh as $ones){
            $sum = $sum + 1;
            $ones->id = $ones->id.$sum;
            // $ones->code = $ones->id.$sum;
            $ones->save();
        }
        return "Done";
    }

    public function destroy($id)
    {
        $rightsLevel3 = RightsLevel3::find($id);
        if ($rightsLevel3) {
            $rightsLevel3->delete($id);
            return redirect()->back()->with('flash_message', 'Rights Level 3 Deleted Successfully');
        } else {
            return redirect()->back()->with('error_message', 'Level Not Found');
        }
    }
}