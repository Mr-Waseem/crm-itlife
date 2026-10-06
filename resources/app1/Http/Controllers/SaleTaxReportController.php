<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\SaleTax;
use App\Models\Setting;

class SaleTaxReportController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        //return $suppliers;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('salestax-report.all-party.create', Compact('encrypted_token', 'parties'));
    }

    public function store(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        //return $supplier;
        $sales = SaleTax::with('saletax_details')->with('parties')->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        //return $sales;
        //$suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('salestax-report.all-party.index', compact('sales', 'encrypted_token', 'suppliers', 'company_detail', 'fromDate', 'toDate'));
    }

    public function SingleParty()
    {
        //return "ehsks";
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        //return $suppliers;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('salestax-report.single-party.create', Compact('encrypted_token', 'parties'));
    }

    public function ShowSingleParty(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $partyID = $request->get('party_name');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        //return $supplier;
        $sales = SaleTax::with('saletax_details')->with('parties')
            ->where('party_id', '=', $partyID)
            ->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        $party = Party::where('id', '=', $partyID)->get();
        //return $sales;
        //$suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('salestax-report.single-party.index', compact('sales', 'encrypted_token', 'suppliers', 'company_detail', 'fromDate', 'toDate', 'party'));
    }

    public function show($id)
    {
        //
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
