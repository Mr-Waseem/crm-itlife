<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AccountSettingController extends Controller
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
        //
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
        $edit = User::findOrFail($id);
        $user = DB::table('users')->where('id', Auth::user()->id)->get();
        return view('account.edit', Compact('edit', 'user', 'id'));
    }

    public function update(Request $request, $id)
    {
        //check which submit was clicked on
        if ($request->get('profile')) {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email'
            ]);
            $user = User::findOrFail($id);

            if (!is_null($request->file('image'))) {
                $imageName = $request->file('image')->getClientOriginalName();
                //return base_path() . '/upload/users/'.$imageName;
                $request->file('image')->move(
                    base_path() . '/upload/users/',
                    $imageName
                );
                $user->update(array(
                    'image' => $imageName
                ));
            }
            $user->update(array(
                'name' => $request->get('name'),
                'email' => $request->get('email')
            ));
            $user->save();

            Session::flash('flash_message', 'Account successfully Updated!');
            return redirect('dashboard');
        } elseif ($request->get('PasswordSubmit')) {
            $this->validate($request, [
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|same:new_password|min:6|different:old_password'
            ]);

            $currentPassword = $request->get("old_password");
            $NewPassword = $request->get("new_password");
            $currentUser = User::findOrFail($id);

            if (Hash::check($currentPassword, $currentUser->password)) {
                $currentUser->fill([
                    'password' => Hash::make($NewPassword)
                ])->save();

                Session::flash('flash_message', 'Password Changed successfully!');
                return redirect('dashboard');
            } else {
                Session::flash('flash_message', 'Current Password is Incorrect!');
                return redirect('account/' . $currentUser->id . '/edit');
            }
        }
    }

    public function destroy($id)
    {
        //
    }
}
