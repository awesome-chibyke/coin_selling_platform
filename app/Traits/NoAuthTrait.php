<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\SuccessfulAccountCreation;

trait NoAuthTrait{

    function createUserAuth($request, $password){

        $credentials = ['email'=>$request->email, 'password'=>$password];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return (object)['status'=>true, 'message'=>'User was successfully logged in', 'data'=>['user'=>Auth()->user()] ];
        }

        return (object)['status'=>false, 'message'=>'The provided credentials do not match our records.', 'data'=>[] ];

    }

    function createUserAccount($request, $password, $uniqueId){
        //create user
        $user = $this->user;
        $user->name = $request->account_name;
        $user->unique_id = $uniqueId;
        $user->email = $request->email;
        $user->password = Hash::make($password);
        $user->email_verified_at = Carbon::now()->toDateTimeString();
        $user->save();
        return $user;
    }

    function sendRegistrationDetails($userObject, $password){
        //get stie settings
        $settingsObject = Settings::first();
        $settingsObject->user = $userObject;
        $settingsObject->password = $password;

        //send a mail to user for account activation
        if(Mail::to($userObject)->send(new SuccessfulAccountCreation($settingsObject) )){
            return true;
        }
        return false;
    }

}