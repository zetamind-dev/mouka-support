<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\Category;
use ComplainDesk\Ticket;
use ComplainDesk\Mailers\AppMailer;
use ComplainDesk\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Auth;
use ComplainDesk\Http\Controllers\LogsController as Log;

class TicketsController extends Controller
{
//
public function __construct()
{
    $this->middleware('auth');
}

public function index()
{
    $tickets = Ticket::orderBy('id', 'desc')->paginate(10);
    $categories = Category::all();

    return view('tickets.index', compact('tickets', 'categories'));
}

public function create()
{
    $categories = Category::all();

    return view('tickets.create', compact('categories'));
}

public function store(Request $request, AppMailer $mailer, SMSController $sms)
{
    $this->validate($request, [
        'title'     => 'required|max:30',
        'category'  => 'required|max:30',
        'priority'  => 'required|max:10',
        'message'   => 'required',
        'picture'   => 'image|nullable|max:1999'
    ]);

    if($request->hasFile('picture')){
        // get File Name with Extension
        $filenameWithExt = $request->file('picture')->getClientOriginalName();
        // Get just Filename
        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
        // Get just Ext
        $extension = $request->file('picture')->getClientOriginalExtension();
        // filename to store
        $fileNameToStore = $filename.'_'.time().'.'.$extension;
        //upload
        $path = $request->file('picture')->storeAs('public/picture', $fileNameToStore);

    } else {
        $fileNameToStore = 'noimage.jpg';
    }
    
    
// Check to see if location is null
$location = $request->input('location');
    // If Loggedin user location is not Head Office, make ticket_id from first two letters of user's loaction and a random int
    if($location != 'Head Office'){
        $ticket_id = strtoupper(mb_substr($location, 0, 2) . random_int(0, 10000000));
    }else{
        $ticket_id = 'HO' . strtoupper(random_int(0, 10000000));
    }


    //dd($request);
    $ticket = new Ticket([
        'title'     => $request->input('title'),
        'user_id'   => Auth::user()->id,
        'ticket_id' => $ticket_id,
        'category_id'  => $request->input('category'),
        'priority'  => $request->input('priority'),
        'message'   => strip_tags($request->input('message')),
        'status'    => "Open",
        'picture'   => $fileNameToStore,
        'location' => $location
        
    ]);

    $ticket->save();

    //$smsMessage = "You just created a Ticket with an ID: $ticket->ticket_id";
    //$userTelephone = Auth::user()->telephone;
    /*
    if (substr($userTelephone, 0, 1) == '0') {
        $userTelephone= substr($userTelephone, 1);
        $telephone = '+233' . $userTelephone;
    }*/
    


    //$mailer->sendTicketInformation(Auth::user(), $ticket);
    //$mailer->SendToCategory($ticket->category->email, $ticket);

        
    return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
    
    /*$smsResponse = $sms->sendSMS($smsMessage, $telephone);

    if ($smsResponse == "200") {
        $mailer->sendTicketInformation(Auth::user(), $ticket);

        return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened.");
    } else {
        $mailer->sendTicketInformation(Auth::user(), $ticket);

        return redirect()->back()->with("status", "A ticket with ID: #$ticket->ticket_id has been opened. SMS Not Sent!");
    }*/
}


public function userTickets()
{
    $tickets = Ticket::where('user_id', Auth::user()->id)->paginate(10);
    $categories = Category::all();

    return view('tickets.user_tickets', compact('tickets', 'categories'));
}



public function show($ticket_id)
{
    $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();
    $comments = $ticket->comments;
    $category = $ticket->category;

    return view('tickets.show', compact('ticket', 'category', 'comments'));
}



public function ticketVisibilityPublic(Log $log, $ticket_id)
{
    $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

    $ticket->visibility = 'public';


    $action = "Changed Ticket Visibility";
    $description = "Changed Ticket with ID: ". $ticket_id . " visibility to Public";
    $userId = Auth::user()->id;

    $ticket->save();

    $log->store($action, $description, $userId);

    return redirect()->back()->with("status", "The Ticket visibility has been updated to Public.");
}

public function ticketVisibilityPrivate(Log $log, $ticket_id)
{
    $ticket = Ticket::where('ticket_id', $ticket_id)->firstOrFail();

    $action = "Changed Ticket Visibility";
    $description = "Changed Ticket with ID: ". $ticket_id . " visibility to Private";
    $userId = Auth::user()->id;

    $ticket->visibility = 'private';

    $ticket->save();

    $log->store($action, $description, $userId);

    return redirect()->back()->with("status", "The Ticket visibility was changed back to Private.");
}
}
