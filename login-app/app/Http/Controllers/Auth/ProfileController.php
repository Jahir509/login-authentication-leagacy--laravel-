<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Hash;

class ProfileController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth');
    }

    public function edit(Request $request){

        $user = $request->user();       //Auth::user()
        return view('auth.profile',compact('user'));
    }

    public function update(Request $request){

        $currentUser = $request->user();
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username'=>['required', 'string', 'max:255', 'unique:users,username,'.$currentUser->id],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$currentUser->id],
            'password' => ['required_with:password_confirmation', 'confirmed'],
            'current_password' => ['required_with:password',function($attribute,$value,$fail) use($request){
                $currentUser = $request->user();
                if(!empty($request->password) && !Hash::check($value,$currentUser->password)){
                    return $fail("Current Password does not match");
                }
            }]
        ]);

        $currentUser->name = $request->name;
        $currentUser->username = $request->username;
        $currentUser->email = $request->email;
        if(!empty($request->password)){
            $currentUser->password = Hash::make($request->password);
        }

        $currentUser->save();
        return redirect()->back()->with('message','Profile Update successfully');
        
    }
}