<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EditUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Method to get Transfer user page
    public function index()
    {

        if (Auth::user()->location === "Head Office") {
            // Retrieve users from Lagos Plant and Head Office only
            $users = User::all()->whereIn('location', ["Lagos", "Head Office"])->where('user_type', 0);
            return view('admin-users.transfer', compact('users'));
        } else {
            // Retrieve users base on the Logged in user location
            $users = User::all()->where('location', Auth::user()->location)->where('user_type', 0);
            return view('admin-users.transfer', compact('users'));
        }
    }

    // Method to transfer users from one plant to another
    public function update(Request $request)
    {
        // Get user by id
        $user = User::find($request->input('userId'));
        if ($user->location === $request->input('location')) {
            return redirect()->back()->with("warning", "No transfer was made, the selected user belongs to the same destination plant");
        } else {
            // Get previous location
            $prev_location = $user->location;
            $user->location = $request->input('location');
            // save
            $user->save();
            return redirect()->back()->with("status", "Transfer successful!     $user->name has been transfered to $user->location from $prev_location");
        }

    }
}
