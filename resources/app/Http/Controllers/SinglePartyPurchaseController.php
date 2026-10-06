<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\Purchase;
use App\Models\Setting;

class SinglePartyPurchaseController extends Controller
{
    public function index()
    {
        //
    }


    public function create()
    {
        $suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        //return $suppliers;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('single-party-purchase-report.create', Compact('encrypted_token', 'suppliers'));
    }

    public function store(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $supplier = $request->get('supplier_name');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        //return $supplier;
        $purchases = Purchase::with('purchase_details')->with('suppliers')->where('supplier_id', '=', $supplier)->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        //return $purchases;
        $suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('single-party-purchase-report.index', compact('purchases', 'encrypted_token', 'suppliers', 'company_detail'));
    }

    public function show($id, Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $supplier = $request->get('supplier_name');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        return $fromDate;
        $purchases = Purchase::with('purchase_details')->with('suppliers')->where('supplier_id', '=', $supplier)->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        return $purchases;
        $suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        return view('single-party-purchase-report.create', compact('purchases', 'encrypted_token', 'suppliers'));
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
