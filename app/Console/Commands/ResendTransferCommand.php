<?php

namespace App\Console\Commands;

use App\Models\PaymentModal;
use Illuminate\Console\Command;
use App\Traits\Payments\ResendTransferTrait;

class ResendTransferCommand extends Command
{
    use ResendTransferTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resend:transfers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resend failed transfers';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
        $paymentModalModelInstance = new PaymentModal();
        $this->resendTransaction($paymentModalModelInstance);
    }
}