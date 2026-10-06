<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseHead;
use App\Models\Expense;
use App\Models\Setting;

class ExpenseHeadReportController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        $ExpenseHeads = ExpenseHead::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        return view('expense-head-report.create', Compact('encrypted_token', 'ExpenseHeads'));
    }

    public function store(Request $request)
    {
        $encrypter = app('Illuminate\Encryption\Encrypter');
        $encrypted_token = $encrypter->encrypt(csrf_token());
        //$supplier = Input::get('supplier_name');
        $ExpenseHead_id = $request->get('expensehead_id');
        $fromDate = $request->get('from_date');
        $toDate = $request->get('to_date');
        //return $fromDate;
        $expenses = Expense::where('expensehead_id', '=', $ExpenseHead_id)->whereBetween('date', [$fromDate, $toDate])->OrderBy('id', 'asc')->get();
        //return $purchases;
        //$suppliers = Supplier::OrderBy('name', 'asc')->pluck('name', 'id')->toArray();
        $company_detail = Setting::where('id', '=', 1)->get();
        return view('expense-head-report.index', compact('expenses', 'encrypted_token', 'company_detail'));
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
