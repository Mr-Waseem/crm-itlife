<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Sales;
use App\Models\Catagory;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;

class UserSaleReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $allUsers = User::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $CurrentUser = User::where('id', '=', Auth::User()->shop_id)->get();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('sales-report.user-sale.create', Compact('encrypted_token', 'allUsers', 'CurrentUser'));
    }

    public function store(Request $request)
    {
        $SaleReport = $request->get('sale_report');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        $UserID = $request->get('user_id');
        $user = User::where('id', '=', $UserID)->get();
        $company_detail = Setting::where('id', '=', 1)->get();

        if ($SaleReport == 1) {
            $sales = Sales::with('sale_details')->with('parties')
                ->where('biller', '=', $UserID)
                ->whereDate('created_at', '>=', $fromDate)
                ->whereDate('created_at', '<=', $toDate)
                ->OrderBy('id', 'asc')->get();

            return view('sales-report.user-sale.index', compact('sales', 'company_detail', 'fromDate', 'toDate', 'user'));
        }

        if ($SaleReport == 2) {

            $product = Catagory::with(['products' => function ($query)
            use ($fromDate, $toDate, $UserID) {
                $query->with(['sale_detail' => function ($query) use ($fromDate, $toDate, $UserID) {
                    $query->where('biller', '=', $UserID);
                    $query->whereDate('created_at', '>=', $fromDate);
                    $query->whereDate('created_at', '<=', $toDate);
                }]);
            }])
                ->OrderBy('id', 'asc')->get();

            return view('sales-report.user-sale.summary', compact('product', 'company_detail', 'fromDate', 'toDate', 'user'));
        }
    }
}
