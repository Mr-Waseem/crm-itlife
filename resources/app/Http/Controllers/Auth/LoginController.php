<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Setting;
use Redirect;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    // protected function authenticated($request, $user){
    //     // return $request;
    //     $uname = "info@itlifee.net";
    //     $pswd = "12345678";
    //     // return "ddss";
    //     if($request->email ==$uname  && $request->password ==$pswd){
    //         return "1";
    //         return redirect('/home');
    //     }
    //     else{
    //         return "12";
    //         return redirect('/login');
    //     }
    // }
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    // public function login(Request $request)
    // {
    //     return $request;
    //     $email = Input::get('CC_AGENT_EMAIL');
    //     $password = Input::get('CC_AGENT_PASS');
    //     $auth = User::where('CC_AGENT_EMAIL', '=', $email)->first();
    //     if($auth){
    //         Auth::login($auth);
    //         return Redirect::to('home');
    //     }
    //     else
    //     {
    //         return Redirect::to('login');
    //     }
    // }

    // public function login(Request $request)
    // {
    //     $username = "mobile@gmail.com";
    //     $password = "12345678";
    //     $email = $request->email;
    //     $password = $request->password;
    //    $auth = User::where('email', '=', $email)->first();

    //     if($auth){
    //         Auth::login($auth);
    //         return Redirect::to('home');
    //     }
    //     else
    //     {
    //         return Redirect::to('login');
    //     }
    // }

    protected function credentials(\Illuminate\Http\Request $request)
    {
        // return $request;
        // return $request->only($this->username(), 'password');
        return ['email' => $request->{$this->username()}, 'password' => $request->password, 'status' => 1];
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/login');
    }
    
}