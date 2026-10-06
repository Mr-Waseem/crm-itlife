<?php

namespace App\Http\Controllers;

use App\Models\VoucherRights;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Party;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $warehouses = Warehouse::OrderBy('id', 'asc')->get();
            return DataTables::of($warehouses)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" name="' . $row->id . '_' . $row->code . '_' . $row->name . '_' . $row->phone . '_' . $row->address . '_' . $row->email . '_' . $row->purchase_account_id . '_' . $row->sale_account_id . '_' . $row->purchasetax_account_id . '_' . $row->saletax_account_id . '_' . $row->tax_account_id . '"><i class="fa fa-pencil"></i></button>&nbsp;
                            <a href="warehouses/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $codes = 1;
        $warehouses = Warehouse::orderBy('id', 'desc')->first();
        if ($warehouses) {
            $codes = $warehouses->code + 1;
        }
        $PurchaseAccount = Party::
            // where('account_type', '=', 'SALES & REVENUE')
            // ->
            whereNotIn('account_type', ['PURCHASER', 'SUPPLIER', 'CUSTOMER', 'EMPLOYEE'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Purchase Voucher Debit Account', '0');

        $SaleAccount = Party::
            // where('account_type', '=', 'SALES & REVENUE')
            // ->
            whereNotIn('account_type', ['PURCHASER', 'SUPPLIER', 'CUSTOMER', 'EMPLOYEE'])
           ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Sale Voucher Credit Account', '0');

            $taxaccount = Party::
            // where('account_type', '=', 'SALES & REVENUE')
            // ->
            whereNotIn('account_type', ['PURCHASER', 'SUPPLIER', 'CUSTOMER', 'EMPLOYEE'])
            ->OrderBy('party_name', 'asc')
            ->pluck('party_name', 'id')
            ->prepend('Tax Account', '0');
        return view('warehouses.index', compact('codes', 'PurchaseAccount', 'SaleAccount', 'taxaccount'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'email' => 'required'
        ]);
        // return $request;
        if ($request->id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'GODOWN')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }


            $edit = Warehouse::findOrFail($request->id);
            $edit->update($request->all());
            return redirect()->back()->with('flash_message', 'GODOWN Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'GODOWN')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            Warehouse::create($request->all());
            return redirect()->back()->with('flash_message', 'GODOWN Added Successfully!');
        }
    }

    public function destroy($id)
    {
        $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
            ->where('voucher_name', 'GODOWN')
            ->where('right_name', 'DELETE')
            ->first();
        if (!$voucherRight) {
            return redirect()->back()->with('access_granted', 'Insufficient Permission.');
        }

        Warehouse::findOrFail($id)->delete();
        return redirect()->back()->with('flash_message', 'GODOWN Deleted Successfully!');
    }
}