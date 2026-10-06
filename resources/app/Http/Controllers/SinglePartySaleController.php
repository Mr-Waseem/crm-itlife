<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Sales;
use App\Models\Setting;

class SinglePartySaleController extends Controller
{
    public function create()
    {
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('sales-report.single-party.create', Compact('encrypted_token', 'parties'));
    }

    public function store(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $party = $request->get('party_name');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $sales = Sales::with('sale_details')->with('parties')->where('party_id', '=', $party)->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        $party = Party::where('id', '=', $party)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('sales-report.single-party.index', compact('sales', 'fromDate', 'toDate', 'encrypted_token', 'party', 'company_detail'));
    }
}
