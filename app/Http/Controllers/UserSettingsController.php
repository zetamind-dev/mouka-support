<?php

namespace ComplainDesk\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use ComplainDesk\User;

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


    // Compare passwords before updating user's table
        $new_password = $request->input('new-password');
        $confirm_password = $request->input('Cpassword');
    if($new_password !== $confirm_password){
        return redirect()->back()->with("invalid", "Password Incorrect");
    
    }else{
        
        $user = User::find(Auth::user()->id);

        $user->telephone = $request->input('telephone');
        $user->employeeno = $request->input('employeeno');
        $password = bcrypt($new_password);
        $user->password = $password;
                
        $user->save();

        return redirect()->back()->with("status", "Your Details has been submitted.");
    }

}


}
