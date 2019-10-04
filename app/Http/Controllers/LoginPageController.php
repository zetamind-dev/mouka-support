<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\Category;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use ComplainDesk\Mailers\AppMailer;
use ComplainDesk\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class LoginPageController extends Controller
{
    //This return the login page view
    public function index(AppMailer $mailer) {
        return view('auth.login');
    }
}