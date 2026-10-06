<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Session;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicle = Vehicle::OrderBy('number', 'asc')->get();
        return view('purchase-milk.vehicles.index', Compact('vehicle'));
    }

    public function create()
    {
        return view('purchase-milk.vehicles.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
            'number' => 'required'
        ]);
        Vehicle::create($request->all());
        Session::flash('flash_message', 'Record Added Successfully!');
        return redirect('vehicles/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $edit = Vehicle::findOrFail($id);
        //return $edit;
        return view('purchase-milk.vehicles.edit', Compact('edit'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'code' => 'required',
            'number' => 'required'
        ]);
        $update = Vehicle::findOrFail($id);
        $update->update($request->all());
        Session::flash('flash_message', 'Record Updated Successfully!');
        return redirect('vehicles');
    }

    public function destroy($id)
    {
        //
    }
}
