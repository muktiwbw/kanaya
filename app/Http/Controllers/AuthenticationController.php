<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Customer;
use App\User;

class AuthenticationController extends Controller
{
    public function adminLoginView(){
        return view('auth.admin-login');
    }

    public function loginView(){
        return view('auth.login');
    }

    public function authenticate(Request $request){
        Auth::guard('users')->logout();
        Auth::guard('customers')->logout();

        $credentials = $request->only('email', 'password');
        $guard = $request->_guard;

        if (Auth::guard($guard)->attempt($credentials)) {
            return $guard == 'users' ? redirect()->route('admin-products-list') : redirect()->route('profile');
        }else{
            return 'Authentication failed';
        }
    }

    public function logout(){
        Auth::guard('users')->logout();
        Auth::guard('customers')->logout();

        return redirect()->route('view-login');
    }

    public function registerView(){
        return view('auth.register');
    }

    public function adminCreateUserView(){
        return view('auth.user-register');
    }

    public function register(Request $request){
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        if($request->_guard == 'users') $data['status'] = 2;

        $registration = $request->_guard == 'users' ? User::create($data) : Customer::create($data);

        return $request->_guard == 'users' ? redirect()->route('admin-users-list') : redirect()->route('view-login');
    }
}
