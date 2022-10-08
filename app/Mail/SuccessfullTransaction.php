<?php

namespace App\Mail;

use App\Models\Settings;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SuccessfullTransaction extends Mailable
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
        return $this->view('email.successful_transaction')->with([
            'user' => $this->settings->user,
            'coin' => $this->settings->coin,
            'coin_name' => $this->settings->coin_name,
            'amount_in_dollar' => $this->settings->amount_in_dollar,
            'amount' => $this->settings->amount_,
            'currency' => $this->settings->currency_,
            'settings'=> $this->settings,
        ]);
    }
}
