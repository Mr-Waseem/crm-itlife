<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sales;

class FindCreditSaleController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('find-credit-sale.create', Compact('encrypted_token'));
    }

    public function store(Request $request)
    {
        $search = $request->get('search');
        //$user = Sales::where('invoice_no','LIKE','%'.$search.'%')
        // ->orWhere('date','LIKE','%'.$search.'%')->get();
        //return $user;
        $sales = Sales::where('invoice_no', 'LIKE', '%' . $search . '%')
            ->where('sale_type', '=', 'Credit Sale')
            //->orWhere('date','LIKE','%'.$search.'%')
            ->OrderBy('id', 'desc')->with(['sale_details' => function ($query) {
                //$query->with('taxes');
                $query->with('discount');
                $query->with('parties');
            }])->with('billers')->get();
        //return $sales;
        return view('find-credit-sale.create', Compact('sales'));
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
