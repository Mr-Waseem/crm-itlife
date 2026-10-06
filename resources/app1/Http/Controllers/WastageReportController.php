<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Setting;
use App\Models\Warehouse;
use App\Models\WastageDetails;

class WastageReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $parties = Party::OrderBy('party_name', 'asc')->pluck('party_name', 'id')->toArray();
        $shops = Warehouse::OrderBy('name', 'asc')->pluck('name', 'id')->prepend('ALL BRANCHES', '0')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('wastage.wastage-report.create', Compact('encrypted_token', 'parties', 'shops'));
    }

    public function store(Request $request)
    {
        $ShopID = $request->get('shop_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $Type = $request->get('sale_report');

        $company_detail = Setting::where('id', '=', 1)->get();
        $shops = Warehouse::where('id', '=', $ShopID)->get();
        if ($Type == 1) {
            if ($ShopID == 0) {
                $sales = WastageDetails::with('wastages')->with('products')
                    ->OrderBy('id', 'asc')->get();
                return view('wastage.wastage-report.index', Compact('sales', 'company_detail', 'shops', 'fromDate', 'toDate'));
            }
            if ($ShopID != 0) {
                $sales = WastageDetails::with('wastages')->with('products')
                    ->where('warehouse_id', '=', $ShopID)
                    ->OrderBy('id', 'asc')->get();
                return view('wastage.wastage-report.index', Compact('sales', 'company_detail', 'shops', 'fromDate', 'toDate'));
            }
        } else {
            if ($ShopID == 0) {
                $sales = WastageDetails::join('wastages', 'wastages.id', '=', 'wastage_details.wastage_id')
                    ->join('products', 'products.id', '=', 'wastage_details.product_id')
                    ->selectRaw('products.product_name')
                    ->selectRaw('sum(quantity) as quantity')
                    ->groupBy('wastage_details.product_id')
                    ->get();
                return view('wastage.wastage-report.index', Compact('sales', 'company_detail', 'shops', 'fromDate', 'toDate'));
            }
        }
    }
}