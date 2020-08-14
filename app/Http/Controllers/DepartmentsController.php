<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Department;
use ComplainDesk\Category;
use ComplainDesk\Http\Controllers\LogsController as Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DepartmentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $departments = Department::all();

        return view('department.index', compact('departments'));
    }

    //Method to Store the department Created
    public function store(Log $log, Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);

        $department = new Department([
            'name' => $request->input('name'),
        ]);

        $action = "Created New Department";
        $description = "Department " . $department->name . " has been Created";
        $userId = Auth::user()->id;

        $department->save();

        $category = new Category([
            'name' => 'All',
            'email' => 'no-reply@test.com',
            'dept_id' => $department->id
        ]);

        $category->save();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "$department->name Department has been created.");
    }

    //Method to detele Category
    public function delete(Log $log, $id)
    {
        $department = Department::where('id', $id)->firstOrFail();

        $action = "Deleted Department";
        $description = "Category " . $department->name . " has been Deleted";
        $userId = Auth::user()->id;

        $department->delete();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("warning", "Department Deleted.");
    }

}
