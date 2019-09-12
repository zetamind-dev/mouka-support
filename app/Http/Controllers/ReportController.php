<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\User;
use ComplainDesk\Ticket;
use ComplainDesk\Category;
use Illuminate\Support\Facades\Auth;


class ReportController extends Controller
{
    public function getComplainList()
    {
        $tickets = Ticket::all()->where('drop_ticket', 1)->where('location', Auth::user()->location);
        $categories = Category::all();
        return view('reports.complainReport',compact('tickets', 'categories'));
    }
}
