<?php

namespace App\Http\Controllers;

use App\Models\GeneralVoucher;
use App\Models\Vouchers;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as DataTables;

class PendingVouchersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $party = Vouchers::with('billers:id,name')
                ->whereStatus(0)
                ->OrderBy('id', 'asc')
                ->get();
            return DataTables::of($party)
                ->addIndexColumn()
                ->addColumn('voucher_date', function ($data) {
                    return date('d/m/Y', strtotime($data->voucher_date));
                })
                ->addColumn('biller', function ($data) {
                    return $data->billers->name;
                })
                ->addColumn('action', function ($row) {
                    $btn = '<a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-success btn-sm approve-voucher"><strong>APPROVE</strong></a> <a href="javascript:void(0)" name="' . $row->id . '" class="btn btn-danger btn-sm remove-voucher"><strong>DELETE</strong></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('pending-vouchers.index');
    }

    public function UpdateStatus($voucher_id)
    {
        $v = Vouchers::find($voucher_id);
        if ($v) {
            Vouchers::find($voucher_id)->update([
                'status' => 1
            ]);

            GeneralVoucher::where('voucher_id', $voucher_id)->update([
                'status' => 1
            ]);

            return redirect()->back()->with('flash_message', 'Voucher has been approved Successfully!');
        } else {
            return redirect()->back()->with('error_message', 'Sorry! Voucher Not Exist');
        }
    }

    public function DeleteVoucher($voucher_id)
    {
        Vouchers::find($voucher_id)->delete();
        GeneralVoucher::where('voucher_id', $voucher_id)->delete();
        return redirect()->back()->with('flash_message', 'Voucher has been deleted Successfully!');
    }
}