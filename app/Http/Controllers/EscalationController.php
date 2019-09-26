<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\Escalation;
use Illuminate\Support\Facades\Auth;
use ComplainDesk\Mailers\AppMailer;

class EscalationController extends Controller
{

  public function index()
    {
        if(Auth::user()->user_type > 1){

          return view('escalation.est-level');
        }
    }

  public function create(Request $request, AppMailer $mailer){
      // Validate user's input
     $this->validate($request, [
        'name' => 'required',
        'email' => 'required',
        'level' => 'required',
        'location' => 'required',
        'format' => 'required',
        'duration'=> 'required'
    ]);


    // Create and save new estlevel to database
    $escalation = new ComplainDesk\Escalation();
    $escalation->name = $request->input('name');
    $escalation->email = $request->input('email');
    $escalation->level = $request->input('level');
    $escalation->location = $request->input('location');
    $escalation->format = $request->input('format');
    $escalation->duration = $request->input('duration');


    // Save to database
    $escalation->save();

    

    return redirect()->back()->with("status", "Escalation level created successfullly!");
  }

}
