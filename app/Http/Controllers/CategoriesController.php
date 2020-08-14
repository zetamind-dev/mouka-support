<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Category;
use ComplainDesk\Department;
use ComplainDesk\Http\Controllers\Controller;
use ComplainDesk\Http\Controllers\LogsController as Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;

class CategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

//Method to Show the Create New Category Vew
    public function create()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        $departments = Department::orderBy('created_at', 'desc')->get();

        return view('category.index', compact('categories', 'departments'));
    }

//Method to Store the Category Created
    public function store(Log $log, Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required',
        //     'email' => 'required',
        // ]);

        $category = new Category([
            'name' => Input::get('name'),
            'email' => Input::get('moderator'),
            'dept_id' =>Input::get('deptModerator')
        ]);

        $action = "Created New Category";
        $description = "Category " . $category->name . " has been Created";
        $userId = Auth::user()->id;

        $category->save();

        $log->store($action, $description, $userId);

        $department = Department::find(Input::get('deptModerator'));

        return redirect()->back()->with("status", "$category->name Category has been created for $department->name department");
    }

//Method to detele Category
    public function delete(Log $log, $id)
    {
        $category = Category::where('id', $id)->firstOrFail();

        $action = "Deleted Category";
        $description = "Category " . $category->name . " has been Deleted";
        $userId = Auth::user()->id;

        $category->delete();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("warning", "Category Deleted.");
    }
}
