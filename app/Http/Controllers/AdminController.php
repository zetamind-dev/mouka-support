<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use ComplainDesk\User;
use ComplainDesk\Http\Controllers\LogsController as Log;
Use ComplainDesk\Department;

class AdminController extends Controller
{
public function __construct()
{
    $this->middleware('auth');
}

public function index()
{

    if(Auth::user()->user_type === 2){
        $users = User::whereNotIn('location', ['Benin', 'Kaduna'])
                ->get();

        $departments = Department::all();

       return view('admin-users.index', compact('users', 'departments'));
    }else{
        

        $departments = Department::all();

       return view('admin-users.index', compact('users', 'departments'));
    }
}


public function store(Log $log, Request $request)
{
    $this->validate($request, [
        'name' => 'required|string|max:255',
        'telephone' => 'required|max:15',
        'email' => 'required|string|email|max:255|unique:users',
    //   'employeeno' => 'required|employeeno',
        'password' => 'required|string|min:6|confirmed',
    ]);
    
    // Check to see if user_type is null
    $user_type = $request->input('user_type');
    if($user_type === null){
        $user_type = 0;
    }

    // Check to see if location is null
    $location = $request->input('location');
    if($location === null){
        $location = Auth::user()->location;
    }

    if ($request->input('password') == $request->input('password_confirmation')) {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telephone' => $request->input('telephone'),
            'employeeno' => $request->input('employeeno'),
            'department_id' => $request->input('department'),
            'password' => bcrypt($request->input('password')),
            'user_type' =>  $user_type,
            'location' =>  $location
        ]);
    }
    
    $action = "Created New User";
    $description = $user->name . " has been Created";
    $userId = Auth::user()->id;

    $user->save();

    $log->store($action, $description, $userId);

    return redirect()->back()->with("status", "User $user->name has been created!");
}

public function create()
{
    if(Auth::user()->user_type > 0){
        $users = User::all()->where('location', Auth::user()->location);
        $departments = Department::all();

        return view('admin-users.index', ['users' => $users,'departments' => $departments]);
    }

    // return view('admin-users.index', compact('admins'));
    // return view('admin-users.index', compact('departments'));
}

//Method to detele Category
public function delete(Log $log, $id)
{
    $user = User::where('id', $id)->firstOrFail();
    // $departments = Department::all();

    $action = "Deleted User";
    $description = "User ". $user->name . " has been deleted";
    $userId = Auth::user()->id;
    
    $user->delete();

    $log->store($action, $description, $userId);

    return redirect()->back()->with("status", "User Deleted.");
}
}
