<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\MailAttachments;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class MailNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $notificatonObject;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mailAttachments = new MailAttachments();
        $mailObject = $this->view('email.notification_mail')
        ->with([
            'settings'=>$this->settings,
            'content'=>$this->settings->content,
            'title'=>$this->settings->title,
            //'date'=>$this->settings->date,
            'user'=>$this->settings->user,
        ]);

        if(count($this->settings->mail_attachments) > 0){
            foreach($this->settings->mail_attachments as $k => $eachAttachmentObject){

                $mailObject->attach(storage_path('app/public/'.$mailAttachments->mailFileStoragePath.$eachAttachmentObject->filename));

            }
        }
        return $mailObject;

    }
}