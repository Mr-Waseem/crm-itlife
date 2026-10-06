<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Product;

class BranchWiseCustomerBalance extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $warehouse = Warehouse::OrderBy('name', 'asc')
            ->where('id', '!=', 1)
            ->pluck('name', 'id')->prepend('Select Warehouse', '')->toArray();
        $products = Product::OrderBy('product_name', 'asc')
            ->pluck('product_name', 'id')->prepend('Select Product', '')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('client-all-report.branch-wise-customer-balance.create', Compact('encrypted_token', 'warehouse', 'products'));
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
