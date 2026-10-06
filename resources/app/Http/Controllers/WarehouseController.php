<?php

namespace App\Http\Controllers;

use App\Models\VoucherRights;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Party;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
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
                    $warehouse = e(json_encode([
                        'id' => $row->id,
                        'code' => $row->code,
                        'name' => $row->name,
                        'phone' => $row->phone,
                        'address' => $row->address,
                        'email' => $row->email,
                        'ntn' => $row->ntn,
                        'strn' => $row->strn,
                        'logo' => $row->logo,
                        'purchase_account_id' => $row->purchase_account_id,
                        'sale_account_id' => $row->sale_account_id,
                        'purchasetax_account_id' => $row->purchasetax_account_id,
                        'saletax_account_id' => $row->saletax_account_id,
                        'tax_account_id' => $row->tax_account_id,
                    ]));

                    $btn = '<div class="btn-group"><button type="button" class="btn btn-primary btn-sm edit_btn" data-warehouse="' . $warehouse . '"><i class="fa fa-pencil"></i></button>&nbsp;
                            <a href="warehouses/destroy/' . $row->id . '" onclick="return confirm(`Are you sure you want to delete this record?`)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></a>
                            </div>';

                    return $btn;
                })
                ->addColumn('logo_thumb', function ($row) {
                    if (!$row->logo) {
                        return '-';
                    }
                    $src = url('resources/upload/warehouses/' . $row->logo);
                    return '<img src="' . e($src) . '" alt="logo" style="max-height:40px;max-width:60px;">';
                })
                ->rawColumns(['action', 'logo_thumb'])
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
            'email' => 'required',
            'ntn' => 'nullable|string|max:100',
            'strn' => 'nullable|string|max:100',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
        ]);

        $data = $request->except(['logo', 'code1', '_token']);

        if ($request->id != null) {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'GODOWN')
                ->where('right_name', 'EDIT')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            $edit = Warehouse::findOrFail($request->id);

            if ($request->hasFile('logo')) {
                if ($edit->logo && File::exists(base_path('upload/warehouses/' . $edit->logo))) {
                    File::delete(base_path('upload/warehouses/' . $edit->logo));
                }
                $file = $request->file('logo');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(base_path('upload/warehouses'), $fileName);
                $data['logo'] = $fileName;
            }

            $edit->update($data);
            return redirect()->back()->with('flash_message', 'GODOWN Updated Successfully!');
        } else {
            $voucherRight = VoucherRights::where('user_id', Auth::User()->id)
                ->where('voucher_name', 'GODOWN')
                ->where('right_name', 'ADD')
                ->first();
            if (!$voucherRight) {
                return redirect()->back()->with('access_granted', 'Insufficient Permission.');
            }

            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(base_path('upload/warehouses'), $fileName);
                $data['logo'] = $fileName;
            }

            Warehouse::create($data);
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

        $warehouse = Warehouse::findOrFail($id);
        if ($warehouse->logo && File::exists(base_path('upload/warehouses/' . $warehouse->logo))) {
            File::delete(base_path('upload/warehouses/' . $warehouse->logo));
        }
        $warehouse->delete();
        return redirect()->back()->with('flash_message', 'GODOWN Deleted Successfully!');
    }
}
