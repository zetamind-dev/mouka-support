<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Category;
use ComplainDesk\Http\Controllers\LogsController as Log;
use ComplainDesk\Http\Controllers\SMSController;
use ComplainDesk\Mailers\AppMailer;
use ComplainDesk\Ticket;
use ComplainDesk\TicketDuration;
use ComplainDesk\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->user_type === 3) {
            $tickets = Ticket::orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('drop_ticket', 0)->paginate(10);
            $categories = Category::all();
            $tickets_duration = TicketDuration::all();

            return view('tickets.index', compact('tickets', 'categories', 'tickets_duration'));

        } elseif (Auth::user()->user_type === 2) {
            $tickets = Ticket::orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('drop_ticket', 0)->whereNotIn('location', ['Benin', 'Kaduna'])->paginate(10);
            $categories = Category::all();
            $tickets_duration = TicketDuration::all();

            return view('tickets.index', compact('tickets', 'categories', 'tickets_duration'));

        } elseif (Auth::user()->user_type === 1) {
            $tickets = Ticket::orderBy('status', 'desc')->orderBy('created_at', 'desc')->where('drop_ticket', 0)->where('location', Auth::user()->location)->paginate(10);
            $categories = Category::all();
            $tickets_duration = TicketDuration::all();

            return view('tickets.index', compact('tickets', 'categories', 'tickets_duration'));
        }

    }

    public function create()
    {
        $categories = Category::all();
        $users = User::all()->where('user_type', 0);
        return view('tickets.create', compact('categories', 'users'));
    }

    public function store(Request $request, AppMailer $mailer, SMSController $sms)
    {
        $this->validate($request, [
            'title' => 'required|max:30',
            'category' => 'required|max:30',
            'priority' => 'required|max:10',
            'message' => 'required',
            'picture' => 'image|nullable|max:1999',
        ]);

        // Hanlde incoming file
        if ($request->hasFile('picture')) {
            // get File Name with Extension
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            // Get just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //upload
            $path = $request->file('picture')->storeAs('public/picture', $fileNameToStore);

        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        /**
         *  Format ticket_id string
         */

        if (Auth::user()->user_type > 0) {
            //it means that ticket is being created by the moderator for the user
            $id = $request->input('userId');
            // find user by id
            $user = User::find($id);
            // set location
            $location = $user->location;

            // Retrieve all tickets
            $tickets = Ticket::all()->where('location', $location);
            // Get total number of tickets
            $total_tickets = count($tickets);

            // Check if total number of tickets is above 0
            if ($total_tickets < 1) {
                // Set ticket_num to one
                $ticket_num = 1;
                $new_id = '000' . $ticket_num;
            } else {
                // Retrieve last inserted ticket from tickets table
                $last_inserted = Ticket::orderBy('created_at', 'desc')->where('location', $location)->first();
                $ticket_num = intval(substr($last_inserted->ticket_id, 2)) + 1;
                // Compare the ticket_num with tickets from the ticket collection base on the user location
                // BEGIN LOOP
                foreach ($tickets as $ticket) {
                    // Check user's location against ticket location
                    if ($ticket->location === $location) { // if it matched
                        // exctract the first two letters of the ticket_id and convert the string to int
                        // check the value if it matches the tiket_num
                        if ($ticket_num === intval(substr($ticket->ticket_id, 2))) { // if it matched
                            // Increment ticket_num by 1 as to avoid duplicate entry in the database
                            $ticket_num++;
                        }
                    }
                }
                // END LOOP
                if ($ticket_num < 10) {
                    $new_id = '000' . $ticket_num;
                } elseif ($ticket_num < 100 && $ticket_num > 10) {
                    $new_id = '00' . $ticket_num;
                } elseif ($ticket_num > 99 && $ticket_num < 1000) {
                    $new_id = '0' . $ticket_num;
                } else {
                    $new_id = $ticket_num;
                }

            }

        } else {
            // Retrieve all tickets
            $tickets = Ticket::all()->where('location', Auth::user()->location);
            // Get total number of tickets
            $total_tickets = count($tickets);

            // Check if total number of tickets is above 0
            if ($total_tickets < 1) {
                // Set ticket_num to 1
                $ticket_num = 1;
                $new_id = '000' . $ticket_num;
            } else {

                // Retrieve last inserted ticket from tickets table
                $last_inserted = Ticket::orderBy('created_at', 'desc')->where('location', Auth::user()->location)->first();
                $ticket_num = intval(substr($last_inserted->ticket_id, 2)) + 1;
                // Compare the ticket_num with tickets from the ticket collection base on the user location
                // BEGIN LOOP
                foreach ($tickets as $ticket) {
                    // Check user's location against ticket location
                    if ($ticket->location === Auth::user()->location) { // if it matched
                        // exctract the first two letters of the ticket_id and convert the string to int
                        // check the value if it matches the tiket_num
                        if ($ticket_num === intval(substr($ticket->ticket_id, 2))) { // if it matched
                            // Increment ticket_num by 1 as to avoid Duplicate entrty in the database
                            $ticket_num++;
                        }
                    }
                }
                // END LOOP

                if ($ticket_num < 10) {
                    $new_id = '000' . $ticket_num;
                } elseif ($ticket_num < 100 && $ticket_num > 10) {
                    $new_id = '00' . $ticket_num;
                } elseif ($ticket_num > 99 && $ticket_num < 1000) {
                    $new_id = '0' . $ticket_num;
                } else {
                    $new_id = $ticket_num;
                }

            }

        }

        // Set user's location
        if (Auth::user()->user_type > 0) { // if true,
            //it means that ticket is being created by the moderator for the user
            $id = $request->input('userId');
            // find user by id
            $user = User::find($id);
            // set location
            $location = $user->location;
        } else { // else
            $location = Auth::user()->location;
        }
        // If Loggedin user location is not Head Office, make ticket_id from first two letters of user's location and a sequential integer number
        if ($location != 'Head Office') {
            $ticket_id = strtoupper(mb_substr($location, 0, 2) . $new_id);
        } else {
            $ticket_id = 'HO' . strtoupper($new_id);
        }

        // Set user_id and ticket_owner
        // Check Login user's access level
        if (Auth::user()->user_type > 0) { // if true
            // then the LoggedIn user is a moderator
            $user_id = $request->input('userId');
            //retrieve regular user's data by Id
            $user = User::find($user_id);
            // set ticket_owner
            $ticket_owner = $user->email;
            // set status
            $status = $request->input('status');
        } else { // the LoggedIn user is a regular user;
            $user_id = Auth::user()->id;
            $ticket_owner = Auth::user()->email;
            $status = "Open";
        }

        //dd($request);
        $ticket = new Ticket([
            'title' => $request->input('title'),
            'user_id' => $user_id,
            'ticket_id' => $ticket_id,
            'category_id' => $request->input('category'),
            'priority' => $request->input('priority'),
            'message' => strip_tags($request->input('message')),
            'status' => $status,
            'picture' => $fileNameToStore,
            'location' => $location,
            'copy_email2' => $request->input('copy_email2'),
            'ticket_owner' => $ticket_owner,
        ]);

        // save ticket details
        $ticket->save();

        // Set ticket_duration
        if ($ticket->status === 'Open') {
            $ticket_duration = new TicketDuration;
            $ticket_duration->ticket_id = $ticket->id;

            $ticket_duration->save();
        }

        //$smsMessage = "You just created a Ticket with an ID: $ticket->ticket_id";
        //$userTelephone = Auth::user()->telephone;
        /*
        if (substr($userTelephone, 0, 1) == '0') {
        $userTelephone= substr($userTelephone, 1);
        $telephone = '+233' . $userTelephone;
        }*/

        // determine user's access level in order to set the mail handler method
        if (Auth::user()->user_type < 1) { // if true
            // then the user is a regular user
            $categories = new Category;
            $mailer->sendTicketInformation(Auth::user(), $ticket);
            // Check user's location to determine moderator
            if (Auth::user()->location === "Head Office" || "Lagos") { // if true
                //then send mail to the moderator at Head Office
                // Fetch moderator at head office
                $moderator = User::all()->where('location', "Head Office")->where('user_type', 2)->first();
                $mailer->SendToModerator($categories, $ticket, $moderator, Auth::user());
            } else {
                // fetch moderator at user's location whose location is not Lagos or Head Office
                $moderator = User::all()->whereNotIn('location', ["Head Office", "Lagos"])->where('location', Auth::user()->location)->where('user_type', 1)->first();
                $mailer->SendToModerator($categories, $ticket, $moderator, Auth::user());
            }
            return redirect()->back()->with("status", "A ticket with ID: $ticket->ticket_id has been opened.");
        } else { // the user is a moderator
            // retrieve the user's id
            $user = User::find($request->input('userId'));
            $categories = new Category;
            if ($user->id === 36) { // For Madam Regina
                return redirect()->back()->with("status", "A ticket with ID: $ticket->ticket_id has been opened for $user->name");
            } else {
                $mailer->sendTicketInformation($user, $ticket);
                return redirect()->back()->with("status", "A ticket with ID: $ticket->ticket_id has been opened for $user->name");
            }
        }

        // Create link for rating IT department

        /*$smsResponse = $sms->sendSMS($smsMessage, $telephone);

    if ($smsResponse == "200") {
    $mailer->sendTicketInformation(Auth::user(), $ticket);

    return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
    } else {
    $mailer->sendTicketInformation(Auth::user(), $ticket);

    return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened. SMS Not Sent!");
    }*/
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required|max:30',
            'category' => 'required|max:30',
            'message' => 'required',
            'picture' => 'image|nullable|max:1999',
        ]);

        if ($request->hasFile('picture')) {
            // get File Name with Extension
            $filenameWithExt = $request->file('picture')->getClientOriginalName();
            // Get just Filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just Ext
            $extension = $request->file('picture')->getClientOriginalExtension();
            // filename to store
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            //upload
            $path = $request->file('picture')->storeAs('public/picture', $fileNameToStore);

        } else {
            $fileNameToStore = 'noimage.jpg';
        }

        //dd($request);
        $ticket = Ticket::find($id);
        $ticket->title = $request->input('title');
        $ticket->message = strip_tags($request->input('message'));
        $ticket->category_id = $request->input('category');
        $ticket->priority = $request->input('priority');

        // Check if user uploaded a new image
        if ($request->hasFile('picture')) {
            $ticket->picture = $fileNameToStore;
        }

        $ticket->save();

        return redirect()->back()->with("status", "Ticket with ID: $ticket->ticket_id has been updated!");
    }

    public function userTickets()
    {
        $tickets = Ticket::where('user_id', Auth::user()->id)->where('drop_ticket', 0)->orderBy('status', 'desc')->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::all();
        //$users = User::all()->where('location', Auth::user()->location);

        return view('tickets.user_tickets', compact('tickets', 'categories'));
    }

    public function show($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments;
        $category = $ticket->category;
        //$users = User::all()->where('location', Auth::user()->location);

        return view('tickets.show', compact('ticket', 'category', 'comments'));
    }

    public function edit($ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
        $comments = $ticket->comments;
        $category = $ticket->category;
        $categories = Category::all();
        //$users = User::all()->where('location', Auth::user()->location);

        return view('tickets.edit', compact('ticket', 'category', 'comments', 'categories'));
    }

    //Method to detele Ticket
    public function drop($id)
    {
        // Find Ticket by id
        $ticket = Ticket::where('id', $id)->firstOrFail();
        $ticket->drop_ticket = 1;
        $ticket->save();
        // Redirect user back to Ticket listing
        return redirect()->back()->with("warning", "Ticket with ID: $ticket->ticket_id has been dropped!");
    }

    public function ticketVisibilityPublic(Log $log, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $ticket->visibility = 'public';

        $action = "Changed Ticket Visibility";
        $description = "Changed Ticket with ID: " . $ticket_id . " visibility to Public";
        $userId = Auth::user()->id;

        $ticket->save();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "The Ticket visibility has been updated to Public.");
    }

    public function ticketVisibilityPrivate(Log $log, $ticket_id)
    {
        $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

        $action = "Changed Ticket Visibility";
        $description = "Changed Ticket with ID: " . $ticket_id . " visibility to Private";
        $userId = Auth::user()->id;

        $ticket->visibility = 'private';

        $ticket->save();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "The Ticket visibility was changed back to Private.");
    }
}
