<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\BulkMail;
use App\Models\Settings;
use App\Models\MailReceivers;
use Illuminate\Bus\Queueable;
use App\Mail\MailNotification;
use App\Models\MailAttachments;
use App\Traits\BulkMailTrait;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class BulkMailSender implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, BulkMailTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $mailId;

    public function __construct($mailId)
    {
        $this->mailId = $mailId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //select the mailto be sent to the users
        $bulkMailInstance = new BulkMail();
        $userModel = new User();
        $settingsModelInstance = new Settings();

        $this->initialMailSending($bulkMailInstance, $userModel, $settingsModelInstance, $this->mailId);
    }

}
