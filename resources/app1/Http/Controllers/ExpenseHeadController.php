<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseHead;
use Illuminate\Support\Facades\Session;

class ExpenseHeadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $heads = ExpenseHead::OrderBy('name', 'asc')->get();
        return view('expenses.heads.index', Compact('heads'));
    }

    public function create()
    {
        return view('expenses.heads.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'description' => 'required'
        ]);
        ExpenseHead::create($request->all());
        Session::flash('flash_message', 'Expense Head created Successfully!');
        return redirect("expenses/heads/create");
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $edit = ExpenseHead::findOrFail($id);
        return view('expenses.heads.edit', Compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $update = ExpenseHead::findOrFail($id);
        $update->update($request->all());
        Session::flash('flash_message', 'Expense Head Update Successfully!');
        return redirect("expenses/heads");
    }

    public function destroy($id)
    {
        //return "hello";
        $update = ExpenseHead::findOrFail($id);
        $update->delete();
        return "Record Deleted Successfully";
    }
}
