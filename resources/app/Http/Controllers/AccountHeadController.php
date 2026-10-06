<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountHead;
use App\Models\Setting;
use Illuminate\Support\Facades\Session;

class AccountHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $heads = AccountHead::OrderBy('title')->get();
        return view('account-head.index', Compact('heads'));
    }

    public function create()
    {
        return view('account-head.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'account_group' => 'required',
            'title' => 'required',
            'account_no' => 'required|unique:account_heads'
        ]);
        AccountHead::create($request->all());
        Session::flash('flash_message', 'Account Head Added Successfully!');
        return redirect('account-head/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $edit = AccountHead::findOrFail($id);
        return view('account-head.edit', Compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'account_no' => 'required'
        ]);
        $update = AccountHead::findOrFail($id);
        $update->update($request->all());
        Session::flash('flash_message', 'Account Head Updated Successfully!');
        return redirect('account-head');
    }

    public function destroy($id)
    {
        $delete = AccountHead::findOrFail($id);
        $delete->delete();
        return "Account Head Deleted Successfully!";
    }

    public function PrintLedger($id)
    {
        $ledgers  = AccountHead::with('ledger_details')->where('id', '=', $id)->get();
        $company_detail = Setting::OrderBy('id', 'asc')->where('id', '=', 1)->get();
        return view('account-head.print', Compact('ledgers', 'company_detail', 'party'));
    }
}
