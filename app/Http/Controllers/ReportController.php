<?php

namespace ComplainDesk\Http\Controllers;

use Carbon\Carbon;
use ComplainDesk\Category;
use ComplainDesk\Exports\TicketsViewExport;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function getComplainList()
    {
        // $tickets = Ticket::all()->where('drop_ticket', 1)->where('location', Auth::user()->location);
        $categories = Category::all();
        return view('reports.create-filter', compact('categories'));
    }

    public function filter(Request $request)
    {

        /**
         * Variables declaration
         */

        $date_from; // This can't be null
        $date_to; // This can't be null
        $status; // Optional
        $priority; //  Optional
        $category; // Optional
        $location; // Optional

        // Check if options are available
        $status = $request->input('status');
        $category = $request->input('category');
        $location = $request->input('location');


        // Get query params
        // $query_log['query'] = array();

        $query_params = array(
            'status' => $status,
            'category' => $category,
            'location' => $location,
        );

        // // Push to query_log
        // array_push($query_log['query'], $query_params);

        // Now query the tickets table
        $tickets = Ticket::orderBy('created_at', 'desc')
            ->orderBy('status', 'desc')
            ->where('status', $status)
            ->where('category_id', $category)
            ->where('location', $location)
            ->paginate(10);

        // Get all categories
        $categories = Category::all();
        $users = User::all();
        return view('reports.filter-list', compact('tickets', 'users', 'categories', 'query_params'));

    }
 
    public function export(Request $request)
    {
        return (new TicketsViewExport($request->status, $request->location))->download('tickets.xlsx');

    }

}
