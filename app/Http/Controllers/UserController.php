<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\User;

class UserController extends Controller
{
    public function users(){
        $users = User::orderBy('updated_at', 'desc')->paginate(10);

        return view('users.users', ['users' => $users]);
    }

    public function customers(){
        $customers = Customer::orderBy('updated_at', 'desc')->paginate(10);

        return view('users.customers', ['customers' => $customers]);
    }
}
