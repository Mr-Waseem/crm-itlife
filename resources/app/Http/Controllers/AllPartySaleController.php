<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Party;
use App\Models\Sales;
use App\Models\Setting;
use App\Models\SaleDetail;
use App\Models\Catagory;
use App\Models\Warehouse;

class AllPartySaleController extends Controller
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
        return view('sales-report.all-party.create', Compact('encrypted_token', 'parties', 'shops'));
    }

    public function store(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        $SaleReport = $request->get('sale_report');
        $ShopID = $request->get('shop_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $company_detail = Setting::where('id', '=', 1)->get();


        if ($ShopID == 0) {
            //BILL WISE ALL BRANCH
            if ($SaleReport == 1) {
                $sales = Sales::with('sale_details')->with('parties')
                    //->whereBetween('date', [$fromDate, $toDate])
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate)
                    ->OrderBy('id', 'asc')->get();
                //return $sales;
                return view('sales-report.all-party.index', compact('sales', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
            //CATEGORY WISE ALL BRANCH
            if ($SaleReport == 2) {
                $product = Catagory::with(['products' => function ($query)
                use ($fromDate, $toDate) {
                    $query->with(['sale_detail' => function ($query) use ($fromDate, $toDate) {
                        $query->whereDate('created_at', '>=', $fromDate);
                        $query->whereDate('created_at', '<=', $toDate);
                    }]);
                }])->OrderBy('id', 'asc')->get();

                return view('sales-report.category-wise.index', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
            //SALE COST ALL BRANCH
            if ($SaleReport == 3) {
                $product = SaleDetail::with('sales')->with('products')
                    //->whereBetween('created_at', [$fromDate, $toDate])
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate)
                    ->OrderBy('id', 'asc')->get();
                return view('sales-report.sale-cost.index', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
            //CATEGORY WISE COST ALL BRANCH
            if ($SaleReport == 4) {
                $product = Catagory::with(['products' => function ($query)
                use ($fromDate, $toDate) {
                    $query->with(['sale_detail' => function ($query) use ($fromDate, $toDate) {
                        $query->whereDate('created_at', '>=', $fromDate);
                        $query->whereDate('created_at', '<=', $toDate);
                    }]);
                }])->OrderBy('id', 'asc')->get();
                return view('sales-report.salecost-category-wise.index', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
        } else {
            //BILL WISE SINGLE BRANCH
            if ($SaleReport == 1) {

                $sales = Sales::with('sale_details')->with('parties')
                    //->whereBetween('date', [$fromDate, $toDate])
                    ->where('warehouse_id', '=', $ShopID)
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate)
                    ->OrderBy('id', 'asc')->get();
                //return $sales;
                return view('sales-report.all-party.index', compact('sales', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
            //CATEGORY WISE SINGLE BRANCH
            if ($SaleReport == 2) {
                //return "enter";
                $product = Catagory::with(['products' => function ($query)
                use ($fromDate, $toDate, $ShopID) {
                    $query->with(['sale_detail' => function ($query) use ($fromDate, $toDate, $ShopID) {
                        $query->whereDate('created_at', '>=', $fromDate);
                        $query->whereDate('created_at', '<=', $toDate);
                        $query->where('warehouse_id', '=', $ShopID);
                    }]);
                }])->OrderBy('id', 'asc')->get();
                return view('sales-report.category-wise.index', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
            //SALE COST SINGLE BRANCH
            if ($SaleReport == 3) {

                $product = SaleDetail::with('sales')->with('products')
                    //->whereBetween('created_at', [$fromDate, $toDate])
                    ->where('warehouse_id', '=', $ShopID)
                    ->whereDate('created_at', '>=', $fromDate)
                    ->whereDate('created_at', '<=', $toDate)
                    ->OrderBy('id', 'asc')->get();

                return view('sales-report.sale-cost.index', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
            //SALE COST CATEGORY SINGLE BRANCH
            if ($SaleReport == 4) {
                $product = Catagory::with(['products' => function ($query)
                use ($fromDate, $toDate, $ShopID) {
                    $query->with(['sale_detail' => function ($query) use ($fromDate, $toDate, $ShopID) {
                        $query->whereDate('created_at', '>=', $fromDate);
                        $query->whereDate('created_at', '<=', $toDate);
                        $query->where('warehouse_id', '=', $ShopID);
                    }]);
                }])->OrderBy('id', 'asc')->get();
                return view('sales-report.salecost-category-wise.index', compact('product', 'encrypted_token', 'company_detail', 'fromDate', 'toDate'));
            }
        }
    }
}
