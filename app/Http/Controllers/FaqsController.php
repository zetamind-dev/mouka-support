<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\Category;
use ComplainDesk\Faq;
use Illuminate\Http\Request;
use Illuminate\Http\User;
use Illuminate\Support\Facades\Auth;

class FaqsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $faqs = Faq::orderBy('created_at', 'desc')->paginate(20);
        return view('faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = Category::all();
        $faqs = Faq::all();
        return view('faqs.create', compact('categories', 'faqs'));

    }

    public function store(Request $request)
    {
        // Validate incoming request
        $this->validate($request, [
            'title' => 'required',
            'category_id' => 'required',
            'body' => 'required',
        ]);

        // Get category name
        $category = Category::find($request->input('category_id'));
        $category_name = $category->name;

        // Cretae new instance of Faq
        $faq = new Faq([
            'title' => $request->input('title'),
            'category_id' => $request->input('category_id'),
            'category_name' => $category_name,
            'author_name' => Auth::user()->name,
            'author_location' => Auth::user()->location,
            'author_id' => Auth::user()->id,
            'body' => strip_tags($request->input('body')),
        ]);

        $faq->save();

        return redirect()->back()->with("status", "You have successfully added a FAQ to the Knowledge base!");
    }

    public function edit($id)
    {
        $faq = Faq::find($id);
        $categories = Category::all();
        return view('faqs.edit', compact('faq', 'categories'));

    }

    public function update(Request $request, $id)
    {
        // Get category name
        $category = Category::find($request->input('category_id'));
        $category_name = $category->name;

        $faq = Faq::find($id);
        $faq->title = $request->input('title');
        $faq->category_id = $request->input('category_id');
        $faq->category_name = $category_name;
        $faq->body = strip_tags($request->input('body'));

        // Save to database
        $faq->save();

        return redirect()->back()->with("status", "Update was successful");
    }

    public function destroy($id)
    {
     // Get faq by id
     $faq = Faq::where('id', $id)->firstOrFail();

     // Delete from database
     $faq->delete();

     return redirect()->back()->with("warning", "FAQ deleted!");
    }
}
