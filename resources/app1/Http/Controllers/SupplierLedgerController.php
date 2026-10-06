<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\SupplierLedgers;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class SupplierLedgerController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::OrderBy('name', 'asc')->get();
        return view('ledger.suppliers.index', Compact('suppliers'));
    }

    public function create()
    {
        $suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id');
        return view('ledger.suppliers.create', Compact('suppliers'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'supplier_id' => 'required',
            'date' => 'required',
            'particulars' => 'required'
        ]);
        SupplierLedgers::create($request->all());
        Session::flash('flash_message', 'Ledger Updated Successfully!');
        return redirect('ledger/suppliers/create');
    }

    public function show($id)
    {
        $ledgers = SupplierLedgers::join('suppliers', 'suppliers.id',  'supplier_ledgers.supplier_id')
            ->where('supplier_id', '=', $id)->OrderBy('id', 'asc')->get(['supplier_ledgers.*', 'suppliers.name', 'suppliers.phone', 'suppliers.city']);
        $party = Supplier::OrderBy('name', 'asc')->where('id', '=', $id)->get();
        $company_detail = Setting::OrderBy('id', 'asc')->where('id', '=', 1)->get();
        return view('ledger.suppliers.details', Compact('ledgers', 'company_detail', 'party', 'id'));
    }

    public function printledger($id)
    {
        $ledgers = SupplierLedgers::join('suppliers', 'suppliers.id',  'supplier_ledgers.supplier_id')
            ->where('supplier_id', '=', $id)->OrderBy('id', 'asc')->get(['supplier_ledgers.*', 'suppliers.name', 'suppliers.phone', 'suppliers.city']);
        $party = Supplier::OrderBy('name', 'asc')->where('id', '=', $id)->get();
        $company_detail = Setting::OrderBy('id', 'asc')->where('id', '=', 1)->get();
        return view('ledger.suppliers.print', Compact('ledgers', 'company_detail', 'party', 'id'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
