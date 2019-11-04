<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Mailers\AppMailer;
use Illuminate\Support\Facades\Auth;

class LoginPageController extends Controller
{
    //This return the login page view
    public function index(AppMailer $mailer)
    {
        return view('auth.login');
    }
}
