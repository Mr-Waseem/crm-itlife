<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Yajra\DataTables\Facades\DataTables;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $warehouses = Departments::with('warehouse:id,name')
           ->OrderBy('code', 'asc')->get();
            return DataTables::of($warehouses)
                ->addIndexColumn()
                ->editColumn('warehouse_name', function ($data) {
                    return $data->warehouse->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->name . '_' . $row->phone . '_' . $row->address . '_' . $row->email . '_'.$row->warehouse_id.'"><i class="fa fa-pencil"></i></button>&nbsp;
                            <a href="departments/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

       $godown=Warehouse::pluck('name','id')->prepend('Select Godown','');

        return view('departments.index', compact('godown'));
    }

    public function store(Request $request)
    {
      
        $this->validate($request, [
            'warehouse_id' => 'required',
            'name' => 'required'
        ]);

        if ($request->id != null) {
            $edit = Departments::findOrFail($request->id);
           
            if($request->warehouse_id!=$request->warehouse_id1){
            $godown=Warehouse::find($request->warehouse_id);
            $deptsum=Departments::where('warehouse_id',$godown->id);
            $count=$deptsum->count()+1;
            $edit->code=$godown->code.$count;
            $edit->save();
            }
            $edit->update($request->all());
            return redirect()->back()->with('flash_message', 'Department Updated Successfully!');
        } else {

            
            $godown=Warehouse::find($request->warehouse_id);
            $deptsum=Departments::where('warehouse_id',$godown->id);
              $count=$deptsum->count()+1;
           $dept=Departments::create($request->all());
            $dept->code=$godown->code.$count;
            $dept->save();
            return redirect()->back()->with('flash_message', 'Department Added Successfully!');
        }
    }

    public function destroy($id)
    {
        Departments::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', 'Department Deleted Successfully!');
        
    }
}
