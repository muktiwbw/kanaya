<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class CustomerController extends Controller
{
    public function profile(){
        return view('customers.profile', ['user' => Auth::guard('customers')->user()]);
    }
}
