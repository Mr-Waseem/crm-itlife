<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\SystemLogo;
use Illuminate\Support\Facades\Session;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //
    }

    public function create()
    {
        return view('settings.create');
    }

    public function store(Request $request)
    {
        if ($request->get('logo')) {
            $imageName = $request->file('image')->getClientOriginalName();
            $request->file('image')->move(public_path() . '/upload/logo/', $imageName);
            //return $imageName;
            $alter = SystemLogo::findOrFail($id);
            $alter->create(array(
                'image' => $imageName
            ));
            //}
            Session::flash('flash_message', 'Image Added Successfully!');
            return redirect('dashboard');
        } elseif ($request->get('settings')) {
            $this->validate($request, [
                'system_name' => 'required',
                'title' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'currency' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required'
            ]);
            //$update = Setting::findOrFail($id);
            $update->create($request->all());
            Session::flash('flash_message', 'Settings Added Successfully!');
            return redirect('dashboard');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //return view('settings.edit');
        $edit = Setting::findOrFail($id);
        $logo = SystemLogo::where('id', '=', 1)->get();
        $settings = Setting::where('id', '=', 1)->get();
        return view('settings.edit', Compact('edit', 'logo', 'settings'));
    }

    public function update(Request $request, $id)
    {
        //$this->validate ($request, ['image' => 'required']);
        if ($request->get('logo')) {
            if (!is_null($request->file('image'))) {
                $imageName = $request->file('image')->getClientOriginalName();
                $request->file('image')->move(base_path() . '/upload/logo/', $imageName);
                //return $imageName;
                $alter = SystemLogo::findOrFail($id);
                $alter->update(array(
                    'image' => $imageName
                ));
            }
            Session::flash('flash_message', 'Image Updated Successfully!');
            return redirect('dashboard');
        } elseif ($request->get('settings')) {
            $this->validate($request, [
                'system_name' => 'required',
                'title' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'currency' => 'required',
                'city' => 'required',
                'state' => 'required',
                'country' => 'required'
            ]);
            $update = Setting::findOrFail($id);
            $update->update($request->all());
            Session::flash('flash_message', 'Settings Updated Successfully!');
            return redirect('dashboard');
        }
    }

    public function destroy($id)
    {
        //
    }
}
