<?php

namespace App\Console\Commands;

use App\Models\BankDetails;
use App\Traits\Payments\FlutterWaveTrait;
use Illuminate\Console\Command;

class BankUpdate extends Command
{
    use FlutterWaveTrait;

    function __construct()
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:banks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command enables this application to load banks from fltterwave serve and update the application';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //return 0;
        $this->loadAndSaveBanks();
    }
}