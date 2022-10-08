<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Traits\HandlePartialPayments;

class ProcessPartialPayment extends Command
{
    use HandlePartialPayments;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'process:partial-payments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pay up the partial payments';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->returnJwtTokenAndProcessPartialPayment();
    }
}
