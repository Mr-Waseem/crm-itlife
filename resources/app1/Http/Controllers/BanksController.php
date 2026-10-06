<?php

namespace App\Http\Controllers;

use App\Models\Party;
use App\Models\VoucherRights;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables as DataTables;

class BanksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $banks = Party::whereRole('Bank')->OrderBy('party_name', 'asc')->get();

            return DataTables::of($banks)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->party_name . '"><i class="fa fa-pencil"></i></button>&nbsp;
                                <a href="javascript:void(0)" class="btn btn-danger btn-sm remove-bank" id="' . $row->id . '"><i class="fa fa-trash"></i></a>
                            </div>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $bank = Party::whereRole('Bank')->orderBy('id', 'desc')->first();
        $codes = 1;
        if ($bank) {
            $codes = $bank->code + 1;
        }

        return view('banks.index', compact('codes'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'party_name' => 'required'
        ], [
            'code.required' => 'The Bank Code field is required',
            'party_name.required' => 'The Bank Name field is required'
        ]);

        if ($request->idd != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'BANKS')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $edit = Party::find($request->idd);
            $edit->update($request->all());
            return redirect()->back()->with('flash_message', "Bank Updated Successfully");
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'BANKS')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $party = Party::create($request->all());
            $party->account_type = "BANK";
            $party->save();

            return redirect()->back()->with('flash_message', "Bank Added Successfully");
        }
        return redirect()->back()->with('error_message', "Something went wrong!!!");
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'BANKS')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }


        $party = Party::find($id);
        if ($party) {
            Party::findOrFail($id)->delete();
            return redirect()->back()->with('flash_message', 'Bank Deleted Successfully!');
        } else {
            return redirect()->back()->with('error_message', 'Something went wrong');
        }
    }
}