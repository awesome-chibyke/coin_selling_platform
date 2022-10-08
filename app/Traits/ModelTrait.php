<?php

namespace App\Traits;

use App\Models\User;
use App\Models\Referal;
use App\Models\Settings;
use App\Models\PaymentModal;

trait ModelTrait{

    function returnPaymentModel(){
        return new PaymentModal();
    }

    function returnReferalModel(){
        return new Referal();
    }

    function returnAppSettingsModel(){
        return new Settings();
    }

    function returnAppUserModel(){
        return new User();
    }
}