<?php

namespace App\Traits;

use App\Mail\MailNotification;
use Illuminate\Support\Facades\Mail;

trait BulkMailTrait{

    function initialMailSending($bulkMailInstance, $userModel, $settingsModelInstance, $BulkMailUniqueId){

        $settingsObject = $settingsModelInstance::first();

        $mailObject = $bulkMailInstance::where('unique_id', $BulkMailUniqueId)->first();
        if($mailObject === null){ exit(); }

        $settingsObject->content = $mailObject->mail_body;//message
        $settingsObject->title = $mailObject->title;//title
        $settingsObject->mail_attachments = $mailObject->mail_attachments;//check for the attachments

        //check if the mail reciver is user
        $this->sendTheMails($mailObject, $bulkMailInstance, $settingsObject, $userModel);
    }

    function sendTheMails($mailObject, $bulkMailInstance, $settingsObject, $userModel){

        if($mailObject->mail_readers === $bulkMailInstance->sendToAllUsers){
            $userArray = $userModel::where('type_of_user', $userModel->normalUserType)->where('email_subcription', $userModel->emailSubcriptionYesStatus)->get();
            if(count($userArray) > 0){
                foreach($userArray as $k => $eachUserObject){
                    $settingsObject->user = $eachUserObject;
                    Mail::to($eachUserObject)->send(new MailNotification($settingsObject));
                }
            }
        }

        if($mailObject->mail_readers === $bulkMailInstance->sendToSelectedUsers){
            $mailReceiverArray = $mailObject->mail_receivers;
            if(count($mailReceiverArray) > 0){
                foreach($mailReceiverArray as $k => $eachReceiverId){
                    $userObject = $userModel::where('unique_id', $eachReceiverId)->where('email_subcription', $userModel->emailSubcriptionYesStatus)->first();
                    if($userObject === null){ continue; }
                    $settingsObject->user = $userObject;
                    Mail::to($userObject)->send(new MailNotification($settingsObject));
                }
            }

        }
    }

}