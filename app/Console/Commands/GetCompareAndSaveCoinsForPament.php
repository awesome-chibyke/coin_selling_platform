<?php

namespace App\Console\Commands;

use App\Traits\NowPaymentsTrait;
use Illuminate\Console\Command;

class GetCompareAndSaveCoinsForPament extends Command
{
    use NowPaymentsTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'coin:market-update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the coins that will transacted in our market';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
        $this->getCompareAndSaveCoins();
    }
}