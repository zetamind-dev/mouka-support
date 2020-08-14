<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Department;
use ComplainDesk\Escalation;
use Illuminate\Http\Request;

class EscalationController extends Controller
{
    public function index()
    {

        //Retrieve escalations level form DB
        $escalations = Escalation::all();
        $departments = Department::all();
        return view('est.create', compact('escalations', 'departments'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'level' => 'required',
            'location' => 'required',
            'format' => 'required',
        ]);

        // Create and save user's input into the database
        $escalation = Escalation::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'level' => $request->input('level'),
            'location' => $request->input('location'),
            'format' => $request->input('format'),
            'department_id' => $request->input('department_id'),
        ]);

        $escalation->save();

        return redirect()->back()->with("status", "A new level has been added successfully");
    }

    public function edit($id)
    {

        $escalation = Escalation::where('id', $id)->firstOrFail();
        $departments = Department::all();

        return view('est.edit', compact('escalation', 'departments'));

    }

    public function update(Request $request, $id)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'level' => 'required',
            'location' => 'required',
            'format' => 'required',
        ]);

        // find escalation by id and save user's input into the database
        $escalation = Escalation::find($id);

        $escalation->name = $request->input('name');
        $escalation->email = $request->input('email');
        $escalation->level = $request->input('level');
        $escalation->location = $request->input('location');
        $escalation->format = $request->input('format');
        $escalation->department_id = $request->input('department_id');

        $escalation->save();

        return redirect()->back()->with("status", "Escalation level has been updated successfully");
    }

    //Method to detele Escalation
    public function delete($id)
    {
        //Find Ticket by id
        $escalation = Escalation::where('id', $id)->firstOrFail();
        //Delete from Database
        $escalation->delete();
        //Redirect back to Escaalation listing
        return redirect()->back()->with("warning", "$escalation->name has been deleted from the escalation level");
    }

}
