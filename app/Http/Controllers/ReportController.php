<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Category;
use ComplainDesk\Exports\TicketExport;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getComplainList()
    {
        $tickets = Ticket::all()->where('drop_ticket', 1)->where('location', Auth::user()->location);
        $categories = Category::all();
        return view('reports.create-filter', compact('tickets', 'categories'));
    }

    public function filter(Request $request)
    {
       return view('reports.filter-list');
    }

    public function export()
    {
        return Excel::download(new TicketExport, 'tickets.xlsx');
    }

}
