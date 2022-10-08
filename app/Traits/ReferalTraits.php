<?php

namespace App\Traits;

use Carbon\Carbon;
use App\Traits\ModelTrait;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Traits\Payments\FlutterWaveTrait;

trait ReferalTraits{

    use ModelTrait, FlutterWaveTrait, Generics;

    function returnReferalBonusPercentage(){
        $settingsModelInstance = $this->returnAppSettingsModel();
        return $settingsModelInstance::first()->referal_percentage;
    }

    function settleReferrer($referedUserObject, $paymentObject){

        if($referedUserObject->referrer_username === null){ return null; }//check if the user has a referrer

        $PaymentModelInstance = $this->returnPaymentModel();
        $userModelInstance = $this->returnAppUserModel();
        $referalModelInstance = $this->returnReferalModel();
        $referrerObject = $userModelInstance::where('username', $referedUserObject->referrer_username)->first();

        if($referrerObject === null){return null;}

        //get the value of the bonus and add it to the db
        //$amountToBeTransfered = $paymentObject->rate * $paymentObject->amount_in_usd;
        $amountToBeTransfered = $this->getAmountInLocalCurrency($paymentObject);
        $bonusPercentage = $this->returnReferalBonusPercentage();
        $bonusAmount = $amountToBeTransfered * ($bonusPercentage/100);

        $uniqueId = $this->createNewUniqueId('referals', 'unique_id', 20);
        $valueToSave = $this->returnReferalObject($uniqueId, $referedUserObject, $referrerObject, $paymentObject, $bonusAmount, $referalModelInstance);
        $saveReferalObject = $this->insertReferalData($valueToSave);
        return $saveReferalObject;

    }

    function returnReferalObject($uniqueId, $referedUserObject, $referrerObject, $paymentObject, $bonusPercentage, $referalModelInstance){
        return (object)[
                'unique_id'=>$uniqueId,
                'reffered_unique_id'=>$referedUserObject->unique_id,
                'refferer_unique_id'=>$referrerObject->unique_id,
                'payment_unique_id'=>$paymentObject->unique_id,
                'amount'=>$bonusPercentage,
                'status'=>$referalModelInstance->pendingStatus,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ];
    }

    function insertReferalData($valueData){
        $PaymentModelInstance = $this->returnReferalModel();

        $PaymentModelInstance->unique_id = $valueData->unique_id;
        $PaymentModelInstance->reffered_unique_id = $valueData->reffered_unique_id;
        $PaymentModelInstance->refferer_unique_id = $valueData->refferer_unique_id;
        $PaymentModelInstance->payment_unique_id = $valueData->payment_unique_id;
        $PaymentModelInstance->amount = $valueData->amount;
        $PaymentModelInstance->status = $valueData->status;
        $PaymentModelInstance->save();
        return $PaymentModelInstance;
    }


    //////////////////////////////transfer the money to the user that wons it/////////////////////////////////
    function transferReferalsToUsers($referalModelInstance, $userModelInstance, $settingsModelInstance, $paymentModalModelInstance){

        //get all the users in the db
        $currentClass = $this;
        $settingsObject = $settingsModelInstance::first();

        $userModelInstance::where('type_of_user', $userModelInstance->normalUserType)->where('status', $userModelInstance->userActiveAccountStatus)->where('email_verified_at', '!=', null)->chunk(200, function ($users) use($referalModelInstance, $currentClass, $settingsObject, $paymentModalModelInstance) {

            $transferArray = []; $referalRecordsSentToFlutterwave = []; $mainAmount = 0; $dataArrayToSave = [];
            $mainBatchId = $currentClass->createNewUniqueId('referals', 'unique_id', 10);

            foreach ($users as $user) {

                //get the referal bonus for this user
                $referalRecords = $referalModelInstance::where('refferer_unique_id', $user->unique_id)->where('status', $referalModelInstance->pendingStatus)->get();
                $activeBank = $currentClass->returnBankForReferalTransfer($user, $referalRecords, $currentClass);
                if($activeBank === false){  continue; }

                $amountDetails = $currentClass->getAmountAndReferalRecordDetails($referalRecords, $currentClass, $mainBatchId, $referalModelInstance, $referalRecordsSentToFlutterwave);

                $amount = $amountDetails->amount;
                $referalRecordsSentToFlutterwave = $amountDetails->referal_records;
                if($amount == 0 || $amount < $settingsObject->least_referal_amount){ continue; }
                $mainAmount += round($amount);

                //add the user record to the
                $uniqueId = $currentClass->createNewUniqueId('payment_modals', 'unique_id', 10);
                $transferArray[] = $currentClass->createReferalTransferObject($activeBank, $amount, $uniqueId, $mainBatchId, $user);

                $dataArrayToSave[] = $currentClass->createDataToSave($uniqueId, $user, $amount, $paymentModalModelInstance, 'NGN');

            }

            if(count($transferArray) == 0){ exit();}
            //Log::info($mainAmount);
            if($currentClass->checkFlutterwaveAccountBalance($mainAmount) === false){exit();}

            $mainTransferObject = $currentClass->returnMainTransferObject($transferArray);
            //Log::info(json_encode($mainTransferObject));
            //send the transfer
            $bulkTransferDetailsFromFlutterWave = $currentClass->makeBulkReferalTransfer($mainTransferObject);
            //Log::info(json_encode($bulkTransferDetailsFromFlutterWave));
            if($bulkTransferDetailsFromFlutterWave->status === false){
                foreach($referalRecordsSentToFlutterwave as $k => $eachRecord){
                    $currentClass->updateReferalStatus($eachRecord, $mainBatchId, $referalModelInstance->pendingStatus);
                }
                exit();
            }

            $paymentModalModelInstance::insert($dataArrayToSave);

        });
    }

    function returnBankForReferalTransfer($user, $referalRecords, $currentClass){
        if(count($referalRecords) > 0){
            $activeBankDetails = $currentClass->getUserActiveBank($user);
            if($activeBankDetails->status === false){
                $this->sendReminderForAdditionOfBankDetails($user);//send a mail to the user to add his/her account details
                return false;
            }
            return $activeBankDetails->data;
        }
        return false;
    }

    function checkFlutterwaveAccountBalance($mainAmount){
        $balanceDetails = $this->getWalletBalance('NGN');
        if($balanceDetails->status === false){ return false; }
        if($mainAmount > $balanceDetails->data->available_balance){
            $this->sendReminderForInsufficientFunds();
            return false;
        }
        return true;
    }

    function getAmountAndReferalRecordDetails($referalRecords, $currentClass, $mainBatchId, $referalModelInstance, $referalRecordsSentToFlutterwave){
        $amount = 0;
        if(count($referalRecords) > 0){
            foreach($referalRecords as $k => $eachReferalRecords){
                $amount += $eachReferalRecords->amount;

                $currentClass->updateReferalStatus($eachReferalRecords, $mainBatchId, $referalModelInstance->processingTransferStatus);

                $referalRecordsSentToFlutterwave[] = $eachReferalRecords;
            }
        }
        return (object)['amount'=>$amount, 'referal_records'=>$referalRecordsSentToFlutterwave];
    }

    function returnMainTransferObject($transferArray){
        return [
            "title"=>"Referal Settlement",
            "bulk_data"=>$transferArray
        ];
    }

    function makeBulkReferalTransfer(array $transferObject):object{

        $url = $this->returnFlutterWaveUrl()->make_bulk_transfer;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
            'Content-Type'=>'application/json'
        ])->post($url, $transferObject);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    function updateReferalStatus($eachReferalRecords, $mainBatchId, $status){
        $eachReferalRecords->transfer_batch_id = $mainBatchId;//save the new
        $eachReferalRecords->status = $status;//save the new
        $eachReferalRecords->save();
    }

    function createDataToSave($uniqueId, $user, $amount, $paymentModalModelInstance, $currency = 'NGN'){
        return [
            'unique_id' => $uniqueId,
            'user_unique_id' => $user->unique_id,
            'amount_transfered' => $amount,
            'description' => 'Referal Settlement',
            'action_type' => $paymentModalModelInstance->transferSettlementTypeForReferal,
            'payment_option' => $paymentModalModelInstance->flutterWaveOption,
            'status' => $paymentModalModelInstance->paymentModalProcessingTransfer,
            'local_currency' => $currency,
        ];
    }

    function createReferalTransferObject($activeBank, $amount, $uniqueId, $mainBatchId, $userObject){
        $explodedName = explode(' ', $userObject->name);

        return [
            "bank_code"=>$activeBank->bank_code,
            "account_number"=>$activeBank->account_number,
            "amount"=>round($amount),
            "currency"=>"NGN",
            "narration"=>"Referal Settlement",
            "reference"=>$uniqueId.'-'.$mainBatchId,
            "meta"=>[
                [
                    "first_name"=> $explodedName[0],
                    "last_name"=> $explodedName[1],
                    "email"=> $userObject->email,
                    "mobile_number"=> $userObject->phone,
                    "recipient_address"=> $userObject->address
                ]
            ]
        ];
    }

    function updateReferalsStatus($allReferalRecords, $status){
        if(count($allReferalRecords) == 0){return false;}
        foreach($allReferalRecords as $eachRecord){
            $eachRecord->status = $status;
            $eachRecord->save();
        }
    }

    function completeFlutterWaveReferalTransferConfirmation($payload, $transferObjectFromDb, $paymentModalModelInstance, $allReferalRecords, $referalModelInstance){

        //check if the data has been updated before
        if($transferObjectFromDb->status === $paymentModalModelInstance->paymentModalCompletedStatus){
            return (object)['status'=>false, 'message'=>'Transfer has been updated before'];
        }

        if($payload['status'] === 'FAILED'){
            $this->updateTransactionStatus($transferObjectFromDb, $paymentModalModelInstance->paymentModalFailedStatus);//save status to failed
            $this->updateReferalsStatus($allReferalRecords, $referalModelInstance->pendingStatus);
            return (object)['status'=>true, 'message'=>'failed transfer has been updated'];
        }

        if($payload['status'] === 'SUCCESSFUL'){
            //update the data
            $this->updateTransactionStatus($transferObjectFromDb, $paymentModalModelInstance->paymentModalCompletedStatus);
            $this->updateReferalsStatus($allReferalRecords, $referalModelInstance->payedStatus);
        }

        return (object)['status'=>true, 'message'=>'Transfer was successfully updated', 'data'=>$transferObjectFromDb];

    }

}