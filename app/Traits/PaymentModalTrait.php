<?php

namespace App\Traits;

trait PaymentModalTrait{

    function savePaymentDetails($paymentDetails, $userObject, $request, $coinConversionRateObject, $paymentModalModelInstance){
        $paymentModal = $paymentModalModelInstance;
        $paymentModal->unique_id = $paymentDetails->data->order_id;
        $paymentModal->user_unique_id = $userObject->unique_id;
        $paymentModal->amount_in_usd = $paymentDetails->data->price_amount;
        $paymentModal->coin = $paymentDetails->data->pay_currency;
        $paymentModal->coin_value = $paymentDetails->data->pay_amount;
        $paymentModal->pay_address = $paymentDetails->data->pay_address;
        $paymentModal->description = $paymentDetails->data->order_description;
        $paymentModal->action_type = $paymentModal->coinSaleActionType;
        $paymentModal->payment_option = $paymentModalModelInstance->nowPaymentOption;
        $paymentModal->reference = $paymentDetails->data->payment_id;
        $paymentModal->coin_name = $request->coin_name;
        $paymentModal->rate = $coinConversionRateObject->rate_in_local_currency;
        $paymentModal->local_currency = $coinConversionRateObject->local_currency;
        $paymentModal->save();
        return $paymentModal;
    }

}