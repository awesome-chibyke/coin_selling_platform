<?php

namespace App\Traits\Payments;

use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Traits\Payments\FlutterWaveTrait;

trait ResendTransferTrait{

    use FlutterWaveTrait;
    //$paymentDataFromDb->status = $paymentModalModelInstance->paymentModalRetryTransferStatus

    function resendTransaction($paymentModalModelInstance){
        //get the failed transactions

        $allTransactionsToBeRetried = $paymentModalModelInstance::where('status', $paymentModalModelInstance->paymentModalRetryTransferStatus)->where('action_type', $paymentModalModelInstance->coinSaleActionType)->get();
        if(count($allTransactionsToBeRetried) == 0){exit(); }

        //loop through failed transactions and create the array of objects for transfer
        $bulkTransferObject = $this->createBulkData($allTransactionsToBeRetried, $paymentModalModelInstance);
        if($bulkTransferObject['status'] === false){ exit();}

        $balanceDetails = $this->getWalletBalance('NGN');
        if($balanceDetails->status === false){ exit(); }
        if($bulkTransferObject['data']['total_sum'] > $balanceDetails->data->available_balance){
            $this->sendReminderForInsufficientFunds();
            exit();
        }

        $data_array_to_be_saved = $bulkTransferObject['data']['data_array_to_be_saved'];
Log::info(json_encode($bulkTransferObject['data']['data_for_transfer']));
        //send the array to flutterwave
        $bulkTransferDetailsFromFlutterWave = $this->makeBulkTransfer($bulkTransferObject['data']['data_for_transfer']);
        if($bulkTransferDetailsFromFlutterWave->status === false){ exit();}

        //loop through the returned data of payments that transfers were queued for
        $this->updateMainPaymentObjects($bulkTransferObject, $paymentModalModelInstance);
        $paymentModalModelInstance::insert($data_array_to_be_saved);


    }

    private function updateMainPaymentObjects($bulkTransferObject, $paymentModalModelInstance){
        foreach($bulkTransferObject['data']['array_of_payment_object_selected_for_transfer'] as $k => $eachPaymentObject){
            $eachPaymentObject->status = $paymentModalModelInstance->paymentModalConfirmedStatus;//change the status of the main payment to comfirmed
            $eachPaymentObject->save();
        }
    }

    function createDataToBeSavedToDb($customTransactionId, $userObject, $amountInLocalCurrency, $status, $paymentDataFromDb, $transferIdFromFlutterWave, $narration){
        $paymentModal = $this->returnPaymentModel();
        return [
            'unique_id' => $customTransactionId,
            'user_unique_id'=> $userObject->unique_id,
            'amount_transfered' => $amountInLocalCurrency,
            'description' => $narration,
            'action_type' => $paymentModal->transferSettlementType,
            'payment_option' => $paymentModal->flutterWaveOption,
            'status' => $status,
            'reference' => $transferIdFromFlutterWave,
            'deposit_transaction_id' => $paymentDataFromDb->unique_id,
            'local_currency' => $paymentDataFromDb->local_currency,
            'created_at' => Carbon::now()->toDateTimeString(),
            'updated_at' => Carbon::now()->toDateTimeString(),
        ];
    }

    function createBulkData($allTransactionsToBeRetried, $paymentModalModelInstance){
        $settingsModel = $this->returnAppSettingsModel();
        $settings = $settingsModel::first();
        //create the transfer data
        $transferData = $this->createTransferData($allTransactionsToBeRetried, $paymentModalModelInstance);
        $status = count($transferData['main_transfer_object']) > 0 ? true : false;
        $message = count($transferData['main_transfer_object']) > 0 ? 'Payments for transaction on '.$settings->company_name : 'No data Avalaible';

        return [
                'status' => $status,
                'message'=> $message,
                'data' =>[
                        'data_for_transfer'=>[
                        "title"=>"Settlement for Transactions",
                        "bulk_data"=>$transferData['main_transfer_object']
                    ],
                    'array_of_payment_object_selected_for_transfer'=>$transferData['array_of_payment_object_selected_for_transfer'],
                    'total_sum'=>$transferData['total_sum'],
                    'data_array_to_be_saved'=>$transferData['data_array_to_be_saved']
                ]
            ];

    }

    function createTransferData($allTransactionsToBeRetried, $paymentModalModelInstance){
        $newArray = []; $array_of_payment_object_selected_for_transfer = []; $totalSum = 0; $dataArrayToBeSaved = [];
        foreach($allTransactionsToBeRetried as $k => $eachTransaction){

            $userObject = $eachTransaction->user_object;

            //get the user bank nowPaymentDetails
            $activeBankDetails = $this->getUserActiveBank($userObject);
            if($activeBankDetails->status === false){
                $this->sendReminderForAdditionOfBankDetails($userObject);//send a mail to the user to add his/her account details
                $this->updatePaymentStatusToRetryTransfer($eachTransaction, $paymentModalModelInstance);
                continue;
            }
            $activeBank = $activeBankDetails->data;

            //get amount in local currency
            $amountInLocalCurrency = $this->getAmountInLocalCurrency($eachTransaction, $userObject);

            //transfer object
            $uniqueId = $this->createNewUniqueId('payment_modals', 'unique_id', 20);
            //$mainTransferObject = $this->returnTransferObject($eachTransaction, $activeBank, $amountInLocalCurrency, $uniqueId);
            $mainTransferObject = $this->returnBulkTransferObject($eachTransaction, $activeBank, $amountInLocalCurrency, $uniqueId, $userObject);

            $newArray[] = $mainTransferObject;
            $array_of_payment_object_selected_for_transfer[] = $eachTransaction;
            $totalSum += $mainTransferObject['amount'];

            $dataArrayToBeSaved[] = $this->createDataToBeSavedToDb($uniqueId, $userObject, $amountInLocalCurrency, $paymentModalModelInstance->paymentModalProcessingTransfer, $eachTransaction, null, $mainTransferObject['narration']);

        }
        return ['main_transfer_object'=>$newArray, 'array_of_payment_object_selected_for_transfer'=>$array_of_payment_object_selected_for_transfer, 'total_sum'=>$totalSum, 'data_array_to_be_saved'=>$dataArrayToBeSaved];
    }

    function makeBulkTransfer(array $transferObject):object{

        $url = $this->returnFlutterWaveUrl()->make_bulk_transfer;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
            'Content-Type'=>'application/json'
        ])->post($url, $transferObject);
        //Log::info(json_encode($response->object()));
        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    private function returnBulkTransferObject($paymentDataFromDb, $activeBank, $amountInLocalCurrency, $uniqueId, $userObject){
        $explodedName = explode(' ', $userObject->name);
        return [
                "bank_code"=>$activeBank->bank_code,
                "account_number"=>$activeBank->account_number,
                "amount"=>$amountInLocalCurrency,
                "currency"=>$paymentDataFromDb->local_currency,
                "narration"=>"Settlement for Transaction",
                "reference"=>$uniqueId,
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

}

?>
