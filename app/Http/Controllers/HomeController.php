<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Category;
use ComplainDesk\Comment;
use ComplainDesk\Mailers\AppMailer;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function index(AppMailer $mailer)
    {
// Invoke escalationlevel method
        // $mailer->SendToEscalationLevel();
        /**
         *  This logic enables an admin user to handle tickets from two different location
         *  Based on request, this logic can be remove later in the future
         *
         *  There are three access level based on the user_type value
         *  user_type 3: super user
         *  user_type 2: admin
         *  user_type 1: modeartor
         *  user_type 0: user
         *
         *  user_type 2 should moderate all tickets in Lagos which includes Ikeja and Head Office
         */
        if (Auth::user()->user_type > 0) {
            if (Auth::user()->user_type === 2 && Auth::user()->location === "Head Office") { // If logged in user has user_type of 2 user and location is Head Office
                // Then select all tickets from the tickets table where location is not Benin and Kaduna
                $tickets = Ticket::orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('drop_ticket', 0)
                    ->whereNotIn('location', ['Benin', 'Kaduna'])
                    ->paginate(10);
                // Retrieve all categories in the categories table
                $categories = Category::all();

                // Retrieve
                $totalTicketsClosed = Ticket::where('status', 'Closed')
                    ->whereNotIn('location', ['Benin', 'Kaduna'])->where('drop_ticket', 0)
                    ->get();
                $totalTicketsClosed = count($totalTicketsClosed);

                $totalTicketsOpen = Ticket::where('status', 'Open')
                    ->whereNotIn('location', ['Benin', 'Kaduna'])->where('drop_ticket', 0)
                    ->get();
                $totalTicketsOpen = count($totalTicketsOpen);

                $totalUsers = User::whereNotIn('location', ['Benin', 'Kaduna'])
                    ->get();
                $totalUsers = count($totalUsers);

                $totalTickets = Ticket::whereNotIn('location', ['Benin', 'Kaduna'])->where('drop_ticket', 0)
                    ->get();
                $totalTickets = count($totalTickets);

                $totalComments = null;

                return view('home', compact('tickets', 'categories', 'totalTicketsClosed', 'totalTicketsOpen', 'totalTickets', 'totalUsers', 'totalComments'));
            } elseif (Auth::user()->user_type > 2) {
                $tickets = Ticket::orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('drop_ticket', 0)->paginate(10);
                $categories = Category::all();

                $totalTicketsClosed = Ticket::all()->where('status', 'Closed')->where('drop_ticket', 0);
                $totalTicketsClosed = count($totalTicketsClosed);

                $totalTicketsOpen = Ticket::all()->where('status', 'Open')->where('drop_ticket', 0);
                $totalTicketsOpen = count($totalTicketsOpen);

                $totalUsers = User::all();
                $totalUsers = count($totalUsers);

                $totalTickets = Ticket::all()->where('drop_ticket', 0);
                $totalTickets = count($totalTickets);

                $totalComments = null;

                $moderators = User::all()->where('location', Auth::user()->location);
                return view('home', compact('tickets', 'categories', 'totalTicketsClosed', 'totalTicketsOpen', 'totalTickets', 'totalUsers', 'totalComments', 'moderators'));

            } else {
                $tickets = Ticket::orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('location', Auth::user()->location)->paginate(10)->where('drop_ticket', 0);
                $categories = Category::all();

                $totalTicketsClosed = Ticket::all()->where('status', 'Closed')->where('location', Auth::user()->location)->where('drop_ticket', 0);
                $totalTicketsClosed = count($totalTicketsClosed);

                $totalTicketsOpen = Ticket::all()->where('status', 'Open')->where('location', Auth::user()->location)->where('drop_ticket', 0);
                $totalTicketsOpen = count($totalTicketsOpen);

                $totalUsers = User::all()->where('location', Auth::user()->location);
                $totalUsers = count($totalUsers);

                $totalTickets = Ticket::all()->where('location', Auth::user()->location)->where('drop_ticket', 0);
                $totalTickets = count($totalTickets);

                $totalComments = null;

                $moderators = User::all()->where('location', Auth::user()->location);
                return view('home', compact('tickets', 'categories', 'totalTicketsClosed', 'totalTicketsOpen', 'totalTickets', 'totalUsers', 'totalComments', 'moderators'));
            }

        } else {
            $tickets = Ticket::where('user_id', Auth::user()->id)->where('drop_ticket', 0)->orderBy('status', 'desc')->orderBy('created_at', 'desc')->paginate(10);
            $categories = Category::all();

            $totalTicketsClosed = Ticket::all()->where('user_id', Auth::user()->id)->where('status', 'Closed')->where('drop_ticket', 0);
            $totalTicketsClosed = count($totalTicketsClosed);

            $totalTicketsOpen = Ticket::all()->where('user_id', Auth::user()->id)->where('status', 'Open')->where('drop_ticket', 0);
            $totalTicketsOpen = count($totalTicketsOpen);

            $totalTickets = Ticket::where('user_id', Auth::user()->id)->paginate(10)->where('drop_ticket', 0);
            $totalTickets = count($totalTickets);

            $totalComments = Comment::where('user_id', Auth::user()->id)->paginate(10)->where('drop_ticket', 0);
            $totalComments = count($totalComments);

            $moderators = User::all()->where('location', Auth::user()->location)->where('user_type', 2);
            return view('home', compact('tickets', 'categories', 'totalTicketsClosed', 'totalTicketsOpen', 'totalTickets', 'totalComments', 'moderators'));
        }

    }

}
