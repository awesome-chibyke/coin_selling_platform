<?php

namespace App\Console\Commands;

use App\Traits\ModelTrait;
use App\Traits\ReferalTraits;
use Illuminate\Console\Command;

class TriggerReferalTransfer extends Command
{
    use ModelTrait, ReferalTraits;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'referal:transfer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command triggers the referal transfer';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $settingsModelInstance = $this->returnAppSettingsModel();
        $paymentModalModelInstance = $this->returnPaymentModel();
        $userModelInstance = $this->returnAppUserModel();
        $referalModelInstance = $this->returnReferalModel();
        $this->transferReferalsToUsers($referalModelInstance, $userModelInstance, $settingsModelInstance, $paymentModalModelInstance);
    }
}
