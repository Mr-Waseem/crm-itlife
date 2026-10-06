<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Setting;
use App\Models\LedgerDetailWise;

class LedgerDetailWiseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->prepend('Select Account', '')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('ledger-detail-wise.index', Compact('encrypted_token', 'parties'));
    }

    public function report(Request $request)
    {
        $this->validate($request, [
            'party_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $PartyID = $request->get('party_id');
        //return $PartyID;
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        // $items = LedgerDetailWise::OrderBy('ledger_detail_wise.id', 'asc')
        //          ->join('products', 'products.id', '=', 'ledger_detail_wise.product_id')
        // ->whereBetween('date', [$fromDate, $toDate])
        // ->where('ledger_detail_wise.party_id', '=', $PartyID)
        // ->get();
        $items = LedgerDetailWise::with('products')->OrderBy('ledger_detail_wise.date', 'asc')
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('ledger_detail_wise.party_id', '=', $PartyID)
            //->where('ledger_detail_wise.id', '=', '53')
            ->get();
        //return $items;
        $party = Party::where('id', '=', $PartyID)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('ledger-detail-wise.report-albash', Compact('items', 'company_detail', 'party'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
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
