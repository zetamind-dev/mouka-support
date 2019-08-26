<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\User;
use ComplainDesk\Ticket;
use ComplainDesk\Category;


class ReportController extends Controller
{
    public function getComplainList()
    {
        $tickets = Ticket::all();
        $categories = Category::all();
        return view('reports.complainReport',compact('tickets', 'categories'));
    }
}
