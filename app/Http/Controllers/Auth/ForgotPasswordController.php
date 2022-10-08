<?php

namespace App\Http\Controllers\Auth;

use Exception;
use App\Models\User;
use App\Models\Settings;
use Illuminate\Http\Request;
use App\Mail\PasswordResetSuccess;
use App\Traits\AuthenticationTrait;
use App\Traits\ForgotPasswordTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    use ForgotPasswordTrait, AuthenticationTrait;
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    function __construct(User $user, Settings $settings)
    {
        $this->user = $user;
        $this->settings = $settings;
    }

    //show the page where user will enter his email
    function initiatePasswordReset(){
        return view('auth.passwords.password_reset_request');
    }

    //function that shows the password token form
    function showPasswordTokenForm($userUniqueId){
        return view('auth.passwords.pasword_reset_token', ['user_unique_id'=>$userUniqueId]);
    }

    //show the password reset form
    function showPasswordResetForm($token, $userUniqueId){

        try{

            $user = $this->user::where('unique_id', $userUniqueId)->first();
            if($user === null){ throw new Exception('User record does not exist'); }

            return view('auth.passwords.password_reset_form', ['user_object'=>$user, 'token'=>$token]);

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }

    }

    ////send forgot password to the user email
    function sendForgotPasswordMail(Request $request){

        try{

            $this->validateEmail($request);

            //check if the email exist
            $user = $this->user::where('email', $request->email)->first();
            if($user === null){ throw new Exception('Email adddress does not exist'); }

            //send the mail
            if($this->forwardForgotPasswordMail($this->passwordResetType, $user)){
                return redirect()->route('password-reset-token-form', [$user->unique_id])->with('status', 'A mail bearing a password reset token has been sent to your email address, please provide token to continue');
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    //resend the password token to a user email
    function reSendForgotPasswordMail($userUniqueId){

        try{
            //get teh user involved
            $user = $this->user::where('unique_id', $userUniqueId)->first();
            if($user === null){ throw new Exception('User record does not exist'); }

            //send the mail
            if($this->forwardForgotPasswordMail($this->passwordResetType, $user)){
                return redirect()->route('password-reset-token-form', [$user->unique_id])->with('status', 'A new mail bearing a password reset token has been sent to your email address, please provide token to continue');
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    protected function validatePasswordResetTokenInputs(array $data)
    {
        $validator =  Validator::make($data, [
            'token' => ['required', 'numeric', 'min:6'],
        ]);
        return $validator;
    }


    //validate the token provided by the user
    function validatePasswordResetToken(Request $request){

        try{
            //valdate the inputs
            $this->validatePasswordResetTokenInputs($request->all())->validate();

            //select the user object
            $user = $this->user::where('unique_id', $request->user_unique_id)->first();
            if($user === null){ throw new Exception('User record does not exist'); }

            //validate the token sent by the user
            $tokenStatus = $this->authenticateCode($this->passwordResetType, $user, $request->token);
            if($tokenStatus->status === false){ throw new Exception($tokenStatus->message); }

            //goto the page for password change
            return redirect()->route('password-reset-form', [$request->token, $request->user_unique_id])->with('status', $tokenStatus->message);

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }

    }

    protected function validateResetPasswordInputs(array $data)
    {
        $validator =  Validator::make($data, [
            'password' => ['required', 'string', 'min:8'],//confirmed
        ]);
        return $validator;
    }

    //validate the token provided by the user
    function resetUserPassword(Request $request){

        try{
            //valdate the inputs
            $this->validateResetPasswordInputs($request->all())->validate();

            //select the user object
            $user = $this->user::where('unique_id', $request->user_unique_id)->first();
            if($user === null){ throw new Exception('User record does not exist'); }

            //validate the token sent by the user
            $tokenStatus = $this->authenticateCode($this->passwordResetType, $user, $request->token, 'yes');
            if($tokenStatus->status === false){ throw new Exception($tokenStatus->message); }

            //update the user with the new password
            $user->password = Hash::make($request->password);
            if($user->save()){
                //send a mail to the user for passworrd change success
                $this->sendResetPasswordSuccessMail($user, $this->settings);

                //goto the page for password change
                return redirect()->route('login')->with('status', 'Password was successfully changed');
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }

    }



}