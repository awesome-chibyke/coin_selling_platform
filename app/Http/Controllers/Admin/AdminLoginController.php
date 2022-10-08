<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\Generics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class AdminLoginController extends Controller
{
    use Generics;

    public function index()
    {
        $data = $this->createArrayForView();
        return view('admin.alt_login', $data);
    }


    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'user_email' => 'required|email',
        ]);

        $email = $request->email;
        $password = $request->password;
        $user_email = $request->user_email;
        if (Auth::attempt(['email' => $email, 'password' => $password, 'type_of_user' => 'admin'])) {
            // The user is an admin
            Auth::logout();
            $user = $this->getUserByEmail($user_email);
            if ($user != null) {
                //login the user
                Auth::login($user);
                session(['main_key' => $request->email]);
                return redirect()->route('home');
                //return response()->json(['data' => $user]);
            } else {
                /*return response()->json([
                    'error' => [
                        'message' => 'User email does not exist, please check properly and retry',
                    ]
                ], Response::HTTP_UNAUTHORIZED);*/
                return Redirect::back()->withErrors(['email'=>['User email does not exist, please check properly and retry']]);
            }
        } else {
            /*return response()->json([
                'error' => [
                    'message' => 'Email, or password is incorrect or user is not an adminstrator',
                ]
            ], Response::HTTP_UNAUTHORIZED);*/
            return Redirect::back()->withErrors( ['email'=>['Email or password is incorrect or user is not an administrator']] );
        }
    }

    public function getUserByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
    }

}
