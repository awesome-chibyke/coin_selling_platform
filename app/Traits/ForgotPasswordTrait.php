<?php
namespace App\Traits;

use App\Models\Settings;
use App\Mail\PasswordResetSuccess;
use App\Mail\ForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;

trait ForgotPasswordTrait{

    use AuthenticationTrait, Generics;

    private $resetPasswordTokenExpirationTime = 30;

    function forwardForgotPasswordMail(string $typeOfAuthenticationCode, object $userObject){
        //create the unique code for validation
        $authCodeUniqueId = $this->createNewUniqueId('authentication_codes', 'unique_id', 20);
        $expirationTime = $this->resetPasswordTokenExpirationTime.' '.$this->minConstant; //30 min
        $code = $this->createAuthenticationCode($typeOfAuthenticationCode, $userObject, $authCodeUniqueId, $expirationTime);

        //get stie settings
        $settingsObject = Settings::first();
        $settingsObject->user = $userObject;
        $settingsObject->activation_code = $code->data['code'];
        $settingsObject->warning = $expirationTime;

        //send a mail to user for account activation
        if(Mail::to($userObject)->send(new ForgotPasswordRequest($settingsObject) )){
            return true;
        }
        return false;
    }

    private function sendResetPasswordSuccessMail($user, $settingsModel){
        $settingsObject = $settingsModel::first();
        $settingsObject->user = $user;
        Mail::to($user)->send(new PasswordResetSuccess($settingsObject) );
    }

}