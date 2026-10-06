<?php

namespace App\Http\Controllers;

use App\Models\Catagory;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class ProductCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $categories = Catagory::OrderBy('id', 'asc')->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->catagory_code . '_' . $row->catagory_name . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                            <a href="product-group/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $code = Catagory::orderBy('id', 'desc')->first();
        $codes = 1;
        if ($code) {
            $codes = $code->catagory_code + 1;
        }

        return view('product-categories.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'catagory_code' => 'required',
            'catagory_name' => 'required'
        ], [
            'catagory_code.required' => 'The Product Group Code field is required',
            'catagory_name.required' => 'The Product Group Name field is required'
        ]);

        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PRODUCT GROUP')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $edit = Catagory::find($request->idd);
            $edit->update($request->all());
            return redirect()->back()->with('flash_message', 'Product Group Updated Successfully');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'PRODUCT GROUP')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            Catagory::create($request->all());
            return redirect()->back()->with('flash_message', 'Product Group Added Successfully');
        }
        return redirect()->back()->with('error_message', 'Something went wrong!!!');
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'PRODUCT GROUP')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        Catagory::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', 'Product Group Added Successfully');
    }
}