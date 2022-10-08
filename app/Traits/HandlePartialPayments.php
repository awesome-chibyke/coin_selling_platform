<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Traits\ModelTrait;
use App\Traits\ReferalTraits;
use App\Traits\NowPaymentsTrait;

trait HandlePartialPayments{

    use ModelTrait, NowPaymentsTrait, ReferalTraits;

    function returnJwtTokenAndProcessPartialPayment(){
        $jwtToken = $this->getJwtToken();
        if($jwtToken->status === false){
            return $jwtToken->message;
        }
        $paymentModalInstance = $this->returnPaymentModel();
        $this->processPartialPayment($paymentModalInstance, $jwtToken, 0);
    }

    function processPartialPayment($paymentModalInstance, $jwtToken, $pageCount = 0){

        $date1 = Carbon::now()->toDateString();
        $date2 = Carbon::parse($date1)->addDays(1)->toDateString();
        $paymentListObject = $this->getListOfPayments($pageCount, $date1, $date2, $jwtToken->data);
        if($paymentListObject->status === false){
            exit();
        }

        $mainPaymentData = $paymentListObject->data->data;
        if(count($mainPaymentData) == 0){
            exit();
        }

        foreach($mainPaymentData as $k => $eachPamentObject){
            if($eachPamentObject->payment_status !== 'partially_paid'){ continue;}

            //select the order from db
            $orderObject = $paymentModalInstance::where('unique_id', $eachPamentObject->order_id)->first();
            if($orderObject === null){ continue; }
            if($orderObject->paid_amount !== null){ continue; }

            $coinBaseRate = $eachPamentObject->pay_amount/$eachPamentObject->price_amount;//paid_amount

            $actualAmountInUsd = $eachPamentObject->actually_paid / $coinBaseRate;
            $orderObject->paid_amount = $actualAmountInUsd;
            $orderObject->status = $paymentModalInstance->paymentModalRetryTransferStatus;
            $orderObject->save();

            //settle the referal
            $this->settleReferrer($orderObject->user_object, $orderObject);

        }
        if($pageCount < $paymentListObject->data->pagesCount){
            $pageCount++;
            return $this->processPartialPayment($paymentModalInstance, $jwtToken, $pageCount);
        }
    }

}
