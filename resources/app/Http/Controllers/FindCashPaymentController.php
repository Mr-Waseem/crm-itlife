<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vouchers;

class FindCashPaymentController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('cash-payments.find-cash-payment.create', Compact('encrypted_token'));
    }

    public function store(Request $request)
    {
        $search = $request->get('search');
        //$user = Sales::where('invoice_no','LIKE','%'.$search.'%')
        // ->orWhere('date','LIKE','%'.$search.'%')->get();
        //return $user;


        $sales = Vouchers::where('voucher_no', 'LIKE', '%' . $search . '%')
            ->with(['voucher_details' => function ($query) {
                //$query->with('products');
                //$query->with('uoms');
                //$query->with('discount');
            }])->with('parties')->with('billers')
            ->where('vouchers.v_type', 'Cash Payment')
            ->get();
        //return $sales;
        return view('cash-payments.find-cash-payment.create', Compact('sales'));
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
