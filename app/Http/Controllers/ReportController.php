<?php

namespace ComplainDesk\Http\Controllers;

use Carbon\Carbon;
use ComplainDesk\Category;
use ComplainDesk\Exports\TicketsViewExport;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Http\Request;

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
        // Retrieve all categories
        $categories = Category::all();

        /**
         * Variables declaration
         */

        $date_from; // This can't be null
        $date_to; // This can't be null
        $status_query = []; // Optional
        $category_query = []; // Optional
        $location_query = []; // Optional
        $query_log['query'] = array();

        /**
         * SET INCOMING DATE INPUT REQUETS
         */
        $format = 'Y-m-d';
        $date_from = Carbon::createFromFormat($format, $request->input('date_from'))->timezone('Africa/Lagos')->toDateTimeString();
        $date_to = Carbon::createFromFormat($format, $request->input('date_to'))->timezone('Africa/Lagos')->toDateTimeString();

        // Check if options are available
        $category_id = $request->input('category');
        $location = $request->input('location');

        /**
         * SET CATEGORY QUERY PARAMS
         */

        // Check if all or no category is selected
        if ($category_id === null || $category_id === 'all') {
            // set loop counter
            $index = 0;
            // iterate over the categories result set
            foreach ($categories as $category) {
                // set all categoru id to category_query array
                $category_query[$index] = $category->id;
                $index++;
            }
        } else { // a category is selected by the user
            $category_query[0] = $category_id;
            $category_query[1] = $category_id;
        }

        /**
         * SET STATUS QUERY PARAMS
         */

        // Check if all or no status is selected
        if ($request->input('status') === null || $request->input('status') === 'all') {
            $status_query[0] = 'Open';
            $status_query[1] = 'Closed';
        } else { // a status is selected
            $status_query[0] = $request->input('status');
            $status_query[1] = $request->input('status');
        }

        /**
         *  SET LOCATION QUERY PARAMS
         */
        // Check if all or no location is selected
        if ($location === null || $location === 'all') {
            $location_query[0] = 'Lagos';
            $location_query[1] = 'Head Office';
            $location_query[2] = 'Kaduna';
            $location_query[3] = 'Benin';
        } else { // a location is selected
            $location_query[0] = $location;
            $location_query[1] = $location;
        }

        /**
         * SET QUERY PARAMS FROM INPUT REQUEST
         */
        $query_params = array(
            'status' => $request->input('status') !== null ? $request->input('status') : 'all',
            'category' => $request->input('category') !== null ? $request->input('category') : 'all',
            'location' => $request->input('location') !== null ? $request->input('location') : 'all',
            'date_from' => $date_from,
            'date_to' => $date_to
        );

        // Push to query_log
        array_push($query_log['query'], $query_params);

        // Now query the tickets table
        $tickets = Ticket::orderBy('created_at', 'desc')
            ->whereIn('status', $status_query)
            ->whereIn('category_id', $category_query)
            ->whereIn('location', $location_query)
            ->whereBetween('created_at', [$date_from, $date_to])
            ->where('drop_ticket', 0)
            ->paginate(10);

        $users = User::all();
        return view('reports.filter-list', compact('tickets', 'users', 'categories', 'query_params'));

    }

    public function export(Request $request)
    {

        return (new TicketsViewExport($request->status, $request->location, $request->category, $request->date_from, $request->date_to))->download('tickets.xlsx');

    }

}
