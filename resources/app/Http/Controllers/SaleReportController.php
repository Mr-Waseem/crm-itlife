<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;
use App\Models\Party;
use App\Models\Setting;
use App\Models\Warehouse;
use App\Models\Catagory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SaleReportController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $shop = Warehouse::where('id', '=', Auth::User()->shop_id)->get();
        //return $shop;
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('sale-report.create', Compact('encrypted_token', 'shop'));
    }

    public function store(Request $request)
    {
        // $encrypter = app('Illuminate\Encryption\Encrypter');
        // $encrypted_token = $encrypter->encrypt(csrf_token());	
        //       $fromDate = $request->get('from_date');
        // $toDate = $request->get('to_date');
        // $sales = Sales::with(['parties', 'sale_details'=>function($query){
        // 		$query->with('products');
        // 		}])->
        // 		whereBetween('date', [$fromDate, $toDate])
        // 		->get();
        //$sales=Sales::with(['parties', 'sale_details'=>function($query){
        //$query->with('products');
        //}])->
        //whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();		
        //return $sales;
        //return view('sale-report.create', compact('sales', 'encrypted_token'));

        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $party = $request->get('party_name');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $Type = $request->get('sale_report');
        $ShopID = Auth::User()->shop_id;
        $company_detail = Setting::where('id', '=', 1)->get();
        $shop = Warehouse::where('id', '=', $ShopID)->get();
        $user = User::where('id', '=', Auth::User()->id)->get();
        //return $ShopID;
        // $sales = Sales::with('sale_details')->with('parties')
        // ->where('warehouse_id', '=', $ShopID)
        // ->where('biller', '=', Auth::User()->id)
        // ->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        if ($Type == "1") {
            $sales = Sales::with('sale_details')->with('parties')
                //->whereBetween('date', [$fromDate, $toDate])
                ->where('warehouse_id', '=', $ShopID)
                ->where('biller', '=', Auth::User()->id)
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->OrderBy('id', 'asc')->get();
            //return $sales;

            //return $shop;

            return view('sales-report.sale-point.bill-wise', compact('sales', 'fromDate', 'toDate', 'encrypted_token', 'shop', 'company_detail', 'user'));
        }
        if ($Type == "2") {
            $product = Catagory::with(['products' => function ($query)
            use ($fromDate, $toDate, $ShopID) {
                $query->with(['sale_detail' => function ($query) use ($fromDate, $toDate, $ShopID) {
                    $query->where('warehouse_id', '=', $ShopID);
                    $query->where('biller', '=', Auth::User()->id);
                    $query->whereDate('created_at', '>=', $fromDate);
                    $query->whereDate('created_at', '<=', $toDate);
                }]);
            }])
                ->OrderBy('id', 'asc')->get();

            return view('sales-report.sale-point.category-wise', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate', 'shop', 'user'));
        }
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
