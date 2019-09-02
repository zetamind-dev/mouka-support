<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ComplainDesk\Category;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use ComplainDesk\Comment;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        if (Auth::user()->user_type > 0) {
            $tickets = Ticket::orderBy('id', 'desc')->where('location', Auth::user()->location)->paginate(10);
            $categories = Category::all();

            $totalTicketsClosed = Ticket::all()->where('status', 'Closed')->where('location', Auth::user()->location);
            $totalTicketsClosed = count($totalTicketsClosed);

            $totalTicketsOpen = Ticket::all()->where('status', 'Open')->where('location', Auth::user()->location);
            $totalTicketsOpen = count($totalTicketsOpen);
            
            $totalUsers = User::all();
            $totalUsers = count($totalUsers);

            $totalTickets = Ticket::all()->where('location', Auth::user()->location);
            $totalTickets = count($totalTickets);

            $totalComments = null;

        return view('home', compact('tickets', 'categories', 'totalTicketsClosed', 'totalTicketsOpen', 'totalTickets', 'totalUsers', 'totalComments'));
        
        } else {
            $tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->paginate(10);
            $categories = Category::all();

            $totalTicketsClosed = Ticket::all()->where('user_id', Auth::user()->id)->where('status', 'Closed');
            $totalTicketsClosed = count($totalTicketsClosed);

            $totalTicketsOpen = Ticket::all()->where('user_id', Auth::user()->id)->where('status', 'Open');
            $totalTicketsOpen = count($totalTicketsOpen);

            $totalTickets = Ticket::where('user_id', Auth::user()->id)->paginate(10);
            $totalTickets = count($totalTickets);

            $totalComments = Comment::where('user_id', Auth::user()->id)->paginate(10);
            $totalComments = count($totalComments);

            $moderators = User::all()->where('location', Auth::user()->location);
        }

        return view('home', compact('tickets', 'categories', 'totalTicketsClosed', 'totalTicketsOpen', 'totalTickets','totalComments', 'moderators'));
    }
}
