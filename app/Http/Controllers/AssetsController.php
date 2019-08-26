<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use ComplainDesk\Asset;
use ComplainDesk\Http\Controllers\LogsController as Log;
use Illuminate\Support\Facades\Auth;
class AssetsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        //  $tickets = Ticket::orderBy('id', 'desc')->paginate(10);
        $assets = Asset::all();

        return view('assets.index', compact( 'assets'));
    }

    public function create()
    {
        $assets = Asset::all()->where('employeeno', Auth::user()->employeeno);

        return view('assets.index', compact( 'assets'));
    }



    public function store(Log $log, Request $request)
    {
        $this->validate($request, [
            'computer_type'   => 'required',
            'laptop_name'   => 'required',
            'laptop_model'   => 'required',
            'laptop_serial_no'   => 'required',
            'laptop_duration'   => 'required',


        ]);

        $asset = new Asset([

        'name'     => $request->input('name'),
        'user_id'     => $request->input('user_id'),
        'email'     => $request->input('email'),
        'employeeno'     => $request->input('employeeno'),
        'computer_type'     => $request->input('computer_type'),
        'laptop_name'     => $request->input('laptop_name'),
        'laptop_model'     => $request->input('laptop_model'),
        'laptop_serial_no'     => $request->input('laptop_serial_no'),
        'laptop_duration'     => $request->input('laptop_duration'),
        'remark'     => $request->input('remark'),
        ]);

        $action = "Created New Asset";
        $description = "Asset ". $asset->laptop_name . " has been Created";
        $userId = Auth::user()->id;

        $asset->save();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "$asset->laptop_name Asset has been created.");
    }

    public function delete(Log $log, $id)
    {
        $asset = Asset::where('id', $id)->firstOrFail();
        // $departments = Department::all();

        $action = "Deleted Asset";
        $description = "Asset ". $asset->name . " has been deleted";
        $userId = Auth::user()->id;

        $asset->delete();

        $log->store($action, $description, $userId);

        return redirect()->back()->with("status", "Asset Deleted.");
    }


}
