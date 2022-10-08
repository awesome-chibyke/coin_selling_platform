<?php
namespace App\Traits;

use App\Models\Settings;
use App\Traits\Generics;
use App\Mail\WelcomeToDataSeller;
use App\Mail\AccountActivationMail;
use App\Traits\AuthenticationTrait;
use Illuminate\Support\Facades\Mail;

trait AccountActivationTrait{
    use AuthenticationTrait, Generics;

    public $expirationTime = 30;

    function forwardActivationMail(string $typeOfAuthenticationCode, object $userObject){
        //create the unique code for validation
        $authCodeUniqueId = $this->createNewUniqueId('authentication_codes', 'unique_id', 20);
        $expirationTime = $this->expirationTime.' '.$this->minConstant; //30 min
        $code = $this->createAuthenticationCode($typeOfAuthenticationCode, $userObject, $authCodeUniqueId, $expirationTime);

        //get stie settings
        $settingsObject = Settings::first();
        $settingsObject->user = $userObject;
        $settingsObject->activation_code = $code->data['code'];
        $settingsObject->warning = $expirationTime;

        //send a mail to user for account activation
        if(Mail::to($userObject)->send(new AccountActivationMail($settingsObject) )){
            return true;
        }
        return false;
    }

    function sendAccountActivationSuccessMail(object $user, object $settingsModel):void{
        //send a mail to the user
        $settingsObject = $settingsModel::first();
        $settingsObject->user = $user;
        Mail::to($user)->send(new WelcomeToDataSeller($settingsObject) );
    }

}