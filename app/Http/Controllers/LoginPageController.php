<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;

class LoginPageController extends Controller
{
    //This return the login page view
    public function index(){
        return view('auth.login');
    }
}