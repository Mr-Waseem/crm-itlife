<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Sales;
use App\Models\Purchase;
use App\Models\Setting;

class WareHouseReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $warehouse = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('Select Warehouse', '')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('warehouse-report.index', Compact('encrypted_token', 'warehouse'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'warehouse_id' => 'required',
            'from_date' => 'required',
            'to_date' => 'required'
        ]);
        $WarehouseID = $request->get('warehouse_id');
        //return $PartyID;
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');

        $sales = Sales::OrderBy('sales.invoice_no', 'asc')->with(['sale_details' => function ($query) {
            //$query->with('taxes');
            $query->with('discount');
            $query->with('parties');
        }])
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('sales.warehouse_id', '=', $WarehouseID)
            //->where('ledger_detail_wise.id', '=', '53')
            ->get();
        //return $sales;
        $purchases = Purchase::OrderBy('purchases.date', 'desc')->with(['purchase_details' => function ($query) {
            $query->with('party');
        }])
            ->whereBetween('date', [$fromDate, $toDate])
            ->where('purchases.warehouse_id', '=', $WarehouseID)
            //->where('ledger_detail_wise.id', '=', '53')
            ->get();
        //return $purchases;
        $party = Warehouse::where('id', '=', $WarehouseID)->get();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('warehouse-report.report', Compact('sales', 'purchases', 'company_detail', 'party'));
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
