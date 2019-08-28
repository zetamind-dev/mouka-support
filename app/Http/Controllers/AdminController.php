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
      //  $tickets = Ticket::orderBy('id', 'desc')->paginate(10);
        $departments = Department::all();

        return view('admin-users.index', compact('users', 'departments'));
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
        
        if ($request->input('password') == $request->input('password_confirmation')) {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'telephone' => $request->input('telephone'),
                'employeeno' => $request->input('employeeno'),
                'department_id' => $request->input('department'),
                'password' => bcrypt($request->input('password')),
                'is_admin' =>  $request->input('is_admin'),
                'location' =>  $request->input('location')
            ]);
        }
        
        $action = "Created New User";
        $description = "User ". $user->name . " has been Created";
        $userId = Auth::user()->id;

        $user->save();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "$user->name has been created.");
    }

    public function create()
    {
        $users = User::all();
        $departments = Department::all();

        return view('admin-users.index', ['users' => $users,
            'departments' => $departments]);

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
