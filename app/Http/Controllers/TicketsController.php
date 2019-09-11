<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\Category;
use ComplainDesk\Ticket;
use ComplainDesk\User;
use ComplainDesk\Mailers\AppMailer;
use ComplainDesk\Http\Controllers\SMSController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

if(Auth::user()->user_type === 2){
    $tickets = Ticket::orderBy('id', 'desc')->whereNotIn('location', ['Benin', 'Kaduna'])->paginate(10);
    $categories = Category::all();

    return view('tickets.index', compact('tickets', 'categories'));
    
}elseif(Auth::user()->user_type === 1){
$tickets = Ticket::orderBy('id', 'desc')->where('location', Auth::user()->location)->paginate(10);
$categories = Category::all();

    return view('tickets.index', compact('tickets', 'categories'));
}

}

public function create()
{
$categories = Category::all();
$moderators = User::all();

return view('tickets.create', compact('categories', 'moderators'));
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

/**
 *  Format ticket_id string 
*/


// Retrieve tickets from tickets table 
$tickets = Ticket::all()->where('location', Auth::user()->location);
$count = count($tickets);

// Check if count is less than 10
if($count < 10){// if true
// increment count variable by 1 and assign the value to new_id variable
$new_id = $count + 1;
// concat ticket number with 000
$ticket_num = "000" . $new_id;
    
}else{// ticket numbering should begin with zeros
$new_id = $count + 1;
// concat ticket number with 000
$ticket_num = "00" . $new_id;
}


// Check to see if location is null
$location = $request->input('location');
// If Loggedin user location is not Head Office, make ticket_id from first two letters of user's loaction and a random integer number
if($location != 'Head Office'){
$ticket_id = strtoupper(mb_substr($location, 0, 2) . $ticket_num);
}else{
$ticket_id = 'HO' . strtoupper($ticket_num);
}


// Set Moderators Email


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
'location' => $location,
'copy_email2' => $request->input('copy_email2'),
'ticket_owner' => Auth::user()->email

]);

$ticket->save();


//$smsMessage = "You just created a Ticket with an ID: $ticket->ticket_id";
//$userTelephone = Auth::user()->telephone;
/*
if (substr($userTelephone, 0, 1) == '0') {
$userTelephone= substr($userTelephone, 1);
$telephone = '+233' . $userTelephone;
}*/



$mailer->sendTicketInformation(Auth::user(), $ticket);
$mailer->SendToCategory($ticket->category->email, $ticket);

// Create link for rating IT department


return redirect()->back()->with("status", "A ticket with ID: $ticket->ticket_id has been opened.");

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
'title'     => 'required|max:30',
'category'  => 'required|max:30',
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

//dd($request); 
$ticket = Ticket::find($id);
$ticket->title = $request->input('title');
$ticket->message = strip_tags($request->input('message'));
$ticket->category_id = $request->input('category');
$ticket->priority = $request->input('priority');

// Check if user uploaded a new image
if($request->hasFile('picture')){
    $ticket->picture = $fileNameToStore;
}

$ticket->save();

return redirect()->back()->with("status", "Ticket with ID: $ticket->ticket_id has been updated!");
}

public function userTickets()
{
$tickets = Ticket::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->paginate(10);
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
public function delete($id)
{
// Find Ticket by id
$ticket = Ticket::where('id', $id)->firstOrFail();
    if($ticket->picture != 'noiamge.jpg'){
        // Delete Image
        Storage::delete('public/picture/'.$ticket->picture);
    }
    $ticket->delete();
    // Redirect user back to Ticket listing
    return redirect()->back()->with("status", "Ticket with ID: $ticket->ticket_id has been deleted!");
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
