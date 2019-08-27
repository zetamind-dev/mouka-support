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
            $admin = User::create([
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
        $description = "User ". $admin->name . " has been Created";
        $userId = Auth::user()->id;

        $admin->save();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "$admin->name has been created.");
    }

    public function create()
    {
        $admins = User::all()->where('is_admin', 1);
        $departments = Department::all();

        return view('admin-users.index', ['admins' => $admins,
            'departments' => $departments]);

       // return view('admin-users.index', compact('admins'));
       // return view('admin-users.index', compact('departments'));
    }

    //Method to detele Category
    public function delete(Log $log, $id)
    {
        $admin = User::where('id', $id)->firstOrFail();
       // $departments = Department::all();
    
        $action = "Deleted Admin User";
        $description = "Admin User ". $admin->name . " has been deleted";
        $userId = Auth::user()->id;
        
        $admin->delete();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "Admin Deleted.");
    }
}
