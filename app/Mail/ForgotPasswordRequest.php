<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPasswordRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->view('email.forgot_password_request')->with([
            'user' => $this->settings->user,
            'settings'=> $this->settings,
            'activation_code'=> $this->settings->activation_code,
            'warning'=>'Token expires in '.$this->settings->warning
        ]);
    }
}
