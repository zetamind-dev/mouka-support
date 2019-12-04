<?php

namespace ComplainDesk\Http\Controllers;

use ComplainDesk\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserSettingsController extends Controller
{
//
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {
        $oldTelephone = Auth::user()->telephone;
        $oldEmployeeno = Auth::user()->employeeno;

        return view('settings.index', ['oldTelephone' => $oldTelephone, 'oldEmployeeno' => $oldEmployeeno]);
        //return view('settings.index', compact('oldTelephone'));
    }

    public function updateTelephone(Request $request)
    {
        // Find user by id
        $user = User::find(Auth::user()->id);

        // Check if all input are empty
        if ($request->input('telephone') === null && $request->input('employeeno') === null && $request->input('new-password') === null) {
            return redirect()->back()->with("info", "No update was made");
        }

        $user->telephone = $request->input('telephone') === null ? $user->telephone : $request->input('telephone');
        $user->employeeno = $request->input('employeeno') === null ? $user->employeeno : $request->input('employeeno');
        if ($request->input('new-password') !== null) {
            $password = bcrypt($request->input('new-password'));
            $user->password = $password;
        }
        $user->save();

        return redirect()->back()->with("update", "Your details has been updated successfully!");

    }

}
