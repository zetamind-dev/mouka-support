<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\Test;

class TestController extends Controller
{
    public function index() {
         return view('escalation.sample');
    }
     public function store(Request $request)
    {
        $this->validate($request, [
            'name'   => 'required',
            'email' => 'required',
            'level' => 'required',
            'location' => 'required',
            'format' => 'required',
            'duration' => 'required'
        ]);

        $test = Test::create([
            'name'     => $request->input('name'),
            'email'  => $request->input('email'),
            'level' => $request->input('level'),
            'location' => $request->input('location'),
            'format' => $request->input('format'),
            'duration' => $request->input('duration')
        ]);

        $test->save();

        return redirect()->back()->with("status", "A new level has been added successfully");
    }
}
