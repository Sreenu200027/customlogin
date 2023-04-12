<?php

namespace App\Http\Controllers;
 

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;



class AuthManager extends Controller
{
    function login(){
        if(Auth::check()){
            return redirect(route('home'));  
        }
        return view(view:'login');
    }

    function Registration(){
        if(Auth::check()){
            return redirect(route('home'));  
        }
        return view(view:'Registration');
    }
    function loginpost(Request $request){
     $request->validate([

        'email' => 'required',
        'password' => 'required',
     ]);
    
     $credentials = $request->only('email','password');
     if(Auth::attempt($credentials)){
        return redirect()->intended(route('home'));
     }
     return redirect(route('login'))->with("error"," Login details not valid");

    }

    function RegistrationPost(Request $request){
            $request->validate([
               'name' => 'required',
               'email' => 'required|email|unique:users',
               'password' => 'required',
               
            ]);
            $data['name'] = $request->name;
            $data['email'] = $request->email;
            $data['password'] = Hash::make($request->password);
            $user = User::create($data);
            if(!$user){
                return redirect(route('Registration'))->with("error"," Registration failed, try again");
            }
            return redirect(route('login'))->with("success","Registration Successful");

    }

    function logout(){
        Session::flush();
        Auth::logout();
        return redirect(route('login'));
    }
}
