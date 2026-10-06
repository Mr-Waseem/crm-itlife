<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PhoneBook;
use Illuminate\Support\Facades\Session;

class PhoneBookController extends Controller
{
    public function index()
    {
        $data = PhoneBook::OrderBy('date', 'desc')->get();
        return view('phonebook.index', Compact('data'));
    }

    public function create()
    {
        return view('phonebook.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'phone' => 'required',
            'name' => 'required',
            'type' => 'required'
        ]);
        PhoneBook::create($request->all());
        Session::flash('flash_message', 'PhoneBook Added Successfully!');
        return redirect('phonebook/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $edit = PhoneBook::findOrFail($id);
        return view('phonebook.edit', Compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'phone' => 'required',
            'name' => 'required',
            'type' => 'required'
        ]);
        $update = PhoneBook::findOrFail($id);
        $update->update($request->all());
        Session::flash('flash_message', 'phonebook Updated Successfully!');
        return redirect('phonebook');
    }

    public function destroy($id)
    {
        $delete = PhoneBook::findOrFail($id);
        $delete->delete();
        return "PhoneBook Deleted Successfully!";
    }
}
