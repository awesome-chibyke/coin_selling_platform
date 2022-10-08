<?php

namespace App\Traits\Payments;

use App\Traits\Generics;
use App\Traits\ModelTrait;
use App\Models\BankDetails;
use App\Traits\AppEnvTrait;
use App\Models\PaymentModal;
use App\Models\UserBankDetails;
use App\Mail\SuccessfullTransaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdditionOfBankToProfile;
use App\Mail\InsufficientFlutterwaveBalance;
use App\Events\FlutterWaveTransferConfirmation;

trait FlutterWaveTrait{
    use Generics, AppEnvTrait, ModelTrait;

    function returnKey(){
        $env = $this->returnAppEnviroment();
        $key = $env === 'local' ? '' : '';
        $keyword = $env === 'local' ? 'FLUTTER_WAVE_PRIVATE_KEY_LOCAL' : 'FLUTTER_WAVE_PRIVATE_KEY_PRODUCTION';
        return env($keyword, $key);
    }

    function returnWebhookSecret(){

        $env = $this->returnAppEnviroment();
        $key = $env === 'local' ? '' : '';
        $keyword = $env === 'local' ? 'fLUTTER_WAVE_WEBHOOH_SECRET_LOCAL' : 'fLUTTER_WAVE_WEBHOOH_SECRET_PRODUCTION';
        return env($keyword, $key);
    }

    function returnFlutterWaveUrl($beneficiaryId = '', $currency = 'NGN'):object{
        return (object)[
            'add_beneficiaries'=>'https://api.flutterwave.com/v3/beneficiaries',
            'get_beneficiaries'=>'https://api.flutterwave.com/v3/beneficiaries',
            'delete_beneficiaries'=>'https://api.flutterwave.com/v3/beneficiaries/'.$beneficiaryId,
            'get_bank_list'=>'https://api.flutterwave.com/v3/banks/NG',
            'make_transfer'=>'https://api.flutterwave.com/v3/transfers',
            'make_bulk_transfer'=>'https://api.flutterwave.com/v3/bulk-transfers/',
            'balance'=>'https://api.flutterwave.com/v3/balances/'.$currency
        ];
    }

    function addBeneficiaries(float $account_number, string $bank_code, string $beneficiary_name):object{

        $url = $this->returnFlutterWaveUrl()->add_beneficiaries;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
            'Content-Type'=>'application/json'
        ])->post($url, [
            "account_number"=>$account_number,
            "account_bank"=>"$bank_code",
            "beneficiary_name"=>$beneficiary_name,
            "currency"=>"NGN"
        ]);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    function getAllBeneficiaries(){
        $url = $this->returnFlutterWaveUrl()->get_beneficiaries;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
        ])->get($url);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    function getWalletBalance($currency){
        $url = $this->returnFlutterWaveUrl('',$currency)->balance;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
        ])->get($url);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    //delete a beneficiary
    function deleteABeneficiary($id){
        $url = $this->returnFlutterWaveUrl($id)->delete_beneficiaries;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
        ])->delete($url);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    // get bank list
    function getBankList():object{

        $url = $this->returnFlutterWaveUrl()->get_bank_list;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key
        ])->get($url);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>'API call failed'];
    }


    function loadAndSaveBanks():void{
        $bankListObject = $this->getBankList();

        if($bankListObject->status === false){exit();}

        //get and loop through the data and save to database
        $this->saveAndUpdateBanks($bankListObject);
    }

    function bankModelInsatnce(){
        return  new BankDetails();
    }

    private function saveAndUpdateBanks($bankListObject){

        if(count($bankListObject->data) > 0){
            $count = 0;
            foreach($bankListObject->data as $k => $eachBankObject){
                $bankModelInsatnce = $this->bankModelInsatnce();
                $checkBank = $bankModelInsatnce::where('code', $eachBankObject->code)->first();

                if($checkBank !== null){
                    $checkBank->main_id = $eachBankObject->id;
                    $checkBank->name = $eachBankObject->name;
                    $checkBank->save();
                }

                if($checkBank === null){
                    $newInstance = $this->bankModelInsatnce();
                    $uniqueId = $this->createNewUniqueId('bank_details', 'unique_id', 20);
                    $newInstance->unique_id = $uniqueId;
                    $newInstance->main_id = $eachBankObject->id;
                    $newInstance->name = $eachBankObject->name;
                    $newInstance->code = $eachBankObject->code;
                    $newInstance->save();
                }
            }
        }
        return $checkBank;
    }

    function sendReminderForAdditionOfBankDetails($user){
        $settingsModel = $this->returnAppSettingsModel();
        $settingsObject = $settingsModel::first();
        $settingsObject->user = $user;
        Mail::to($user)->send(new AdditionOfBankToProfile($settingsObject));
    }

    function updatePaymentStatusToRetryTransfer($paymentDataFromDb, $paymentModalModelInstance){
        $paymentDataFromDb->status = $paymentModalModelInstance->paymentModalRetryTransferStatus;
        $paymentDataFromDb->save();
    }

    function sendReminderForInsufficientFunds(){
        $settingsModel = $this->returnAppSettingsModel();
        $settingsObject = $settingsModel::first();
        Mail::to($settingsObject->email1)->send(new InsufficientFlutterwaveBalance($settingsObject));
    }

    function settleUser($paymentDataFromDb, $userObject){

        $paymentModalModelInstance = $this->returnPaymentModalModelInstance();
        $uniqueId = $this->createNewUniqueId('payment_modals', 'unique_id', 20);//unique id for the transafer object
        //get amount in local currency
        $amountInLocalCurrency = $this->getAmountInLocalCurrency($paymentDataFromDb, $userObject);

        //get the user bank nowPaymentDetails
        $activeBankDetails = $this->getUserActiveBank($userObject);
        if($activeBankDetails->status === false){
            $this->sendReminderForAdditionOfBankDetails($userObject);//send a mail to the user to add his/her account details
            $this->updatePaymentStatusToRetryTransfer($paymentDataFromDb, $paymentModalModelInstance);
            return (object)['status'=>false, 'message'=>$activeBankDetails->message, 'data'=>[]];
        }
        $activeBank = $activeBankDetails->data;

        //transfer object
        $mainTransferObject = $this->returnTransferObject($paymentDataFromDb, $activeBank, $amountInLocalCurrency, $uniqueId);
        $balanceDetails = $this->getWalletBalance($mainTransferObject['currency']);
        if($balanceDetails->status === false){ return (object)['status'=>false, 'message'=>$balanceDetails->message ]; }
        if($mainTransferObject['amount'] > $balanceDetails->data->available_balance){
            $this->sendReminderForInsufficientFunds();
            $this->updatePaymentStatusToRetryTransfer($paymentDataFromDb, $paymentModalModelInstance);
            return (object)['status'=>false, 'message'=>'Insufficient balance'.$mainTransferObject['amount'].' '.$balanceDetails->data->available_balance ];
        }

        //make tansafer
        $returnedTransferObjectFromFlutterWave = $this->makeTransfer($mainTransferObject);
        if($returnedTransferObjectFromFlutterWave->status === false){
            //change the status of the payment to retry transfer
            $this->updatePaymentStatusToRetryTransfer($paymentDataFromDb, $paymentModalModelInstance);
            return (object)['status'=>false, 'message'=>$returnedTransferObjectFromFlutterWave->message];
        }

        $savedTransferObject = $this->saveReturnedTransferObjectFromFlutterWaveToDb($returnedTransferObjectFromFlutterWave->data, $mainTransferObject, $userObject, $uniqueId, $amountInLocalCurrency, $paymentDataFromDb, $paymentModalModelInstance->paymentModalProcessingTransfer);

        if($savedTransferObject){
            return (object)['status'=>true, 'message'=>'transfer was successful, checking status'];
        }

    }

    function saveReturnedTransferObjectFromFlutterWaveToDb(object $transferObjectFromFlutterWave, array $mainTransferObject, object $userObject, string $uniqueId, float $amountInLocalCurrency, object $paymentDataFromDb, string $status){
        $paymentModal = $this->returnPaymentModalModelInstance();
        $paymentModal->unique_id = $uniqueId;
        $paymentModal->user_unique_id = $userObject->unique_id;
        $paymentModal->amount_transfered = $amountInLocalCurrency;
        $paymentModal->description = $mainTransferObject['narration'];
        $paymentModal->action_type = $paymentModal->transferSettlementType;
        $paymentModal->payment_option = $paymentModal->flutterWaveOption;
        $paymentModal->status = $status;
        $paymentModal->reference = $transferObjectFromFlutterWave->id;
        $paymentModal->deposit_transaction_id = $paymentDataFromDb->unique_id;
        $paymentModal->local_currency = $paymentDataFromDb->local_currency;
        $paymentModal->save();
        return $paymentModal;
    }

    function makeTransfer(array $transferObject):object{

        $url = $this->returnFlutterWaveUrl()->make_transfer;
        $key = $this->returnKey();

        $response = Http::withHeaders([
            'Authorization'=>'Bearer '.$key,
            'Content-Type'=>'application/json'
        ])->post($url, $transferObject);

        return isset($response->object()->status) && $response->object()->status === "success" ?
        (object)['status'=>true, 'data'=>$response->object()->data] :
        (object)['status'=>false, 'message'=>$response->object()->message];
    }

    function returnTransferObject(object $paymentDataFromDb, object $activeBank, float $amountInLocalCurrency, string $uniqueId){
        return [
            "account_bank"=>$activeBank->bank_code,//"044",
            "account_number"=>$activeBank->account_number,
            "amount"=>$amountInLocalCurrency,
            "narration"=>"Settlement for Transaction",
            "currency"=>$paymentDataFromDb->local_currency,//"NGN",
            "reference"=>$uniqueId
        ];
    }

    function getUserActiveBank(object $userObject) :object{
        $userBankModelInstance = $this->returnUserBankModelInstance();
        $userBankDetails = $userObject->user_bank_details->where('status', $userBankModelInstance->activeStatus)->first();

        return $userBankDetails === null ? (object)['status'=>false, 'message'=>'User Bank details does not exist', 'data'=>[]]:
        (object)['status'=>true, 'message'=>'User Bank details was returned', 'data'=>$userBankDetails];
    }

    function getAmountInLocalCurrency(object $paymentDataFromDb): float{
        return $paymentDataFromDb->paid_amount !== null ?
        $paymentDataFromDb->paid_amount * $paymentDataFromDb->rate : $paymentDataFromDb->amount_in_usd * $paymentDataFromDb->rate;
    }

    function returnUserBankModelInstance(){
        return new UserBankDetails();
    }

    function returnPaymentModalModelInstance(){
        return new PaymentModal();
    }

    function completeFlutterWaveTransferConfirmation($payload, $transferObjectFromDb, $paymentModalModelInstance){

        //check if the data has been updated before
        if($transferObjectFromDb->status === $paymentModalModelInstance->paymentModalCompletedStatus){
            return (object)['status'=>false, 'message'=>'Transfer has been updated before'];
        }

        $mainTransactionDetails = $paymentModalModelInstance::where('unique_id',$transferObjectFromDb->deposit_transaction_id)->first();

        if($payload['status'] === 'FAILED'){
            $this->updateTransactionStatus($transferObjectFromDb, $paymentModalModelInstance->paymentModalFailedStatus);//save status to failed
            $mainTransactionDetails !== null ? $this->updateTransactionStatus($mainTransactionDetails, $paymentModalModelInstance->paymentModalRetryTransferStatus) : '';//save status to failed
            return (object)['status'=>true, 'message'=>'failed transfer has been updated'];
        }

        if($payload['status'] === 'SUCCESSFUL'){
            //update the data
            $this->updateTransactionStatus($transferObjectFromDb, $paymentModalModelInstance->paymentModalCompletedStatus);
            //send mail
            $this->sendSuccessfulTransferMessage($transferObjectFromDb->user_object, $mainTransactionDetails->coin, $mainTransactionDetails->coin_name, $mainTransactionDetails->amount_in_usd, $transferObjectFromDb->amount_transfered, $mainTransactionDetails->local_currency);
            $theData = ['flutter_wave_data'=>$payload, 'db_data'=>$transferObjectFromDb->data];
        }

        event(new FlutterWaveTransferConfirmation($theData));//send the event to te front end
        return (object)['status'=>true, 'message'=>'Transfer was successfully updated', 'data'=>$transferObjectFromDb];

    }

    function sendSuccessfulTransferMessage($user, $coin, $coin_name, $amount_in_dollar, $amount_, $currency_){
        $settingsModel = $this->returnAppSettingsModel();
        $settingsObject = $settingsModel::first();
        $settingsObject->user = $user;
        $settingsObject->coin = $coin;
        $settingsObject->coin_name = $coin_name;
        $settingsObject->amount_in_dollar = $amount_in_dollar;
        $settingsObject->amount_ = $amount_;
        $settingsObject->currency_ = $currency_;
        Mail::to($user)->send(new SuccessfullTransaction($settingsObject));
    }

    function updateTransactionStatus($transferObjectFromDb, $status){
        $transferObjectFromDb->status = $status;
        $transferObjectFromDb->save();
    }

}