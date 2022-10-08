<?php
namespace App\Traits;

use Carbon\Carbon;
use App\Models\Referal;
use App\Models\Settings;
use App\Traits\Generics;
use App\Traits\ModelTrait;
use App\Models\PaymentModal;
use App\Traits\ReferalTraits;
use App\Models\UserBankDetails;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdditionOfBankToProfile;
use App\Models\CoinMarketDetailsUpdate;
use App\Models\PremiumSubscriptionModel;
use App\Traits\Payments\FlutterWaveTrait;

trait NowPaymentsTrait{

    use Generics, FlutterWaveTrait, AppEnvTrait, ReferalTraits, ModelTrait;

    public $nowPaymentMainPath = 'https://nowpayments.io/';

    function returnNowPaymentKey(){
        $env = $this->returnAppEnviroment();
        $key = $env === 'local' ? '' : '';
        $keyword = $env === 'local' ? 'NOW_PAYMENT_KEY_LOCAL' : 'NOW_PAYMENT_KEY_PRODUCTION';
        return env($keyword, $key);
    }

    function returnNowPaymentUrl($amount = 0, $toCurrency = 'usd', $paymentId = '', $currency_from = 'btc', $pageNo = 0, $date1 = '', $date2 = ''){
        $env = $this->returnAppEnviroment();
        $urlObject = $env === 'local' ?  (object)[
            'estimated_amount_url'=>"https://api-sandbox.nowpayments.io/v1/estimate?amount=$amount&currency_from=usd&currency_to=$toCurrency",
            'minimum_amount_url'=>"https://api-sandbox.nowpayments.io/v1/min-amount?currency_from=$currency_from",
            'full_currencies_url'=>"https://api-sandbox.nowpayments.io/v1/full-currencies",
            'available_coin_for_payments_url'=>"https://api-sandbox.nowpayments.io/v1/merchant/coins",
            'payment_status_url'=>"https://api-sandbox.nowpayments.io/v1/payment/$paymentId",
            'create_payment_url'=>"https://api-sandbox.nowpayments.io/v1/payment",
            'payment_list'=>"https://api-sandbox.nowpayments.io/v1/payment/?limit=500&page=$pageNo&sortBy=created_at&orderBy=asc&dateFrom=$date1&dateTo=$date2",
            'jwt_token'=>"https://api.nowpayments.io/v1/auth"
        ] : (object)[
            'estimated_amount_url'=>"https://api.nowpayments.io/v1/estimate?amount=$amount&currency_from=usd&currency_to=$toCurrency",
            'minimum_amount_url'=>"https://api.nowpayments.io/v1/min-amount?currency_from=$currency_from",
            'full_currencies_url'=>"https://api.nowpayments.io/v1/full-currencies",
            'available_coin_for_payments_url'=>"https://api.nowpayments.io/v1/merchant/coins",
            'payment_status_url'=>"https://api.nowpayments.io/v1/payment/$paymentId",
            'create_payment_url'=>"https://api.nowpayments.io/v1/payment",
            'payment_list'=>"https://api.nowpayments.io/v1/payment/?limit=500&page=$pageNo&sortBy=created_at&orderBy=asc&dateFrom=$date1&dateTo=$date2",
            'jwt_token'=>"https://api.nowpayments.io/v1/auth"
        ];

        return $urlObject;
    }

    function getPaymentAPIStatus(): string{
        $env = $this->returnAppEnviroment();
        $url = $env === 'local' ? "https://api.nowpayments.io/v1/status":"https://api.nowpayments.io/v1/status";
        $url = env('NOW_PAYMENT_API_STATUS_URL');
        $response = Http::get($url);
        if($response->ok()){
            return $response->object()->message;
        }
        return 'NOT-OK';
    }

    function getEstimatedAmount($amount, $toCurrency, $currency_from = 'usd'){
        $env = $this->returnAppEnviroment();
        $url = $env === 'local' ? "https://api-sandbox.nowpayments.io/v1/estimate?amount=$amount&currency_from=$currency_from&currency_to=$toCurrency" :
        "https://api-sandbox.nowpayments.io/v1/estimate?amount=$amount&currency_from=$currency_from&currency_to=$toCurrency";
        $key = $this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key
        ])->get($url);

        if(isset($response->object()->estimated_amount)){
            return (object)['status'=>true, 'data'=>$response->object()];
        }
        return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server'];
    }

    function getMinimumAmount(){

        $url = $this->returnNowPaymentUrl(0, 'usd', '', 'btc')->minimum_amount_url;
        $key = $$this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key
        ])->get($url);

        if(isset($response->object()->currency_to)){
            return $response->object();
        }
        return (object)[];
    }


    function getAvailableCurrencies(){

        $url = $this->returnNowPaymentUrl()->full_currencies_url;
        $key = $this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key
        ])->get($url);

        if(isset($response->object()->currencies)){
            return (object)['status'=>true, 'data'=>$response->object()->currencies];
        }

        return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server'];
    }

    //bring in coind and save them
    function getAvailableCoinForPayments():object{

        $url = $this->returnNowPaymentUrl()->available_coin_for_payments_url;
        $key = $this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key
        ])->get($url);

        if(isset($response->object()->selectedCurrencies)){
            return (object)['status'=>true, 'data'=>$response->object()->selectedCurrencies];
        }

        return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server'];
    }//https://api.nowpayments.io/v1/auth

    //bring in coind and save them
    function getListOfPayments($pageNo, $date1, $date2, $jwtToken){

        $url = $this->returnNowPaymentUrl(0, 'usd', '', 'btc', $pageNo, $date1, $date2)->payment_list;
        $key = $this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key,
            'Authorization'=>'Bearer '.$jwtToken
        ])->get($url);

        if(isset($response->object()->data)){
            return (object)['status'=>true, 'data'=>$response->object()];
        }

        return (object)['status'=>false, 'message'=>$response->object()->message];
    }

    function getJwtToken():object{

        $url = $this->returnNowPaymentUrl()->jwt_token;
        $key = $this->returnNowPaymentKey();

        $response = Http::post($url, [
            "email"=> "digitalklubvpservices@gmail.com",
            "password"=> "1980SSa@#"
        ]);

        if (isset($response->object()->token)){
            return (object)['status'=>true, 'data'=>$response->object()->token];
        }else{
            return (object)['status'=>false, 'message'=>$response->object()->message];
        }
    }

    function getCompareAndSaveCoins(){

        $allAvalableCoinsForPayment = $this->getAvailableCoinForPayments();
        if($allAvalableCoinsForPayment->status === false){
            return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server'];
        }
        $allAvalableCoins = $this->getAvailableCurrencies();
        if($allAvalableCoins->status === false){
            return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server'];
        }

        //compare the curencies to make get a better object
        $selectArrayOfObject = $this->compareCurrencies($allAvalableCoinsForPayment->data, $allAvalableCoins->data);

        if(count($selectArrayOfObject) > 0){
            $savedData = $this->updateCoinMarketTable($selectArrayOfObject);
            if($savedData){
                return ['status'=>true, 'message'=>'Data was successfull saved'];
            }
        }

        return ['status'=>false, 'message'=>'Something went wrong'];

    }

    function compareCurrencies($allAvalableCoinsForPayment, $allAvailableCoin){
        echo json_encode($allAvalableCoinsForPayment);
        $newObject = []; $selectArrayOfObject = [];
        foreach($allAvailableCoin as $k => $eachAvailableCoin){
            $newObject[strtolower($eachAvailableCoin->code)] = $eachAvailableCoin;
        }

        foreach($allAvalableCoinsForPayment as $m => $eachAvailableCoinForPayment){
            $keyToCheck = strtolower($eachAvailableCoinForPayment);
            if (array_key_exists($keyToCheck, $newObject) ){
                $selectArrayOfObject[] = $newObject[strtolower($eachAvailableCoinForPayment)];
            }
        }

        //save the selected object to db
        return $selectArrayOfObject;
    }

    function returnCoinMarketTable(){
        return new CoinMarketDetailsUpdate();
    }

    function updateCoinMarketTable($selectArrayOfObject){

        if(count($selectArrayOfObject) > 0){

            $coinMarketDetailsUpdate = $this->returnCoinMarketTable();

            $checkExistence = $coinMarketDetailsUpdate::where('id', 1)->first();

            if($checkExistence === null){
                //create the unique key
                $unique_id = $this->createNewUniqueId('coin_market_details_updates', 'unique_id');
                $coinMarketDetailsUpdate = $coinMarketDetailsUpdate;
                $coinMarketDetailsUpdate->unique_id = $unique_id;
                $coinMarketDetailsUpdate->content = json_encode($selectArrayOfObject);
                $coinMarketDetailsUpdate->save();
                return $coinMarketDetailsUpdate;
            }
            if($checkExistence !== null){
                $checkExistence->content = json_encode($selectArrayOfObject);
                $checkExistence->save();
                return $checkExistence;
            }
        }
    }

    function getPaymentStatus($paymentId){

        $url = $this->returnNowPaymentUrl(0, 'usd', $paymentId)->payment_status_url;
        $key = $$this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key
        ])->get($url);
        return $response->object();
        if(isset($response->object()->currencies)){
            return $response->object()->currencies;
        }

        return (array)[];
    }

    function createPayment($price_amount, $order_description, $order_id, $coin){

        $url = $this->returnNowPaymentUrl()->create_payment_url;
        $key = $this->returnNowPaymentKey();

        $response = Http::withHeaders([
            'x-api-key'=>$key,
            'Content-Type'=>'application/json'
        ])->post($url, [
            "price_amount"=> $price_amount,
            "price_currency"=> "usd",
            "pay_currency"=> $coin,//btc
            "ipn_callback_url"=> URL::to('/')."/api/confirm-payment",
            "order_id"=> $order_id,
            "order_description"=> $order_description
        ]);

        if (isset($response->object()->payment_id)){
            return (object)['status'=>true, 'data'=>$response->object()];
        }else{
            return (object)['status'=>false, 'message'=>$response->object()->message];
        }

    }

    //create the now payment session
    function interactWithNowPayments($pendingPaymentObject, $selectedPaymentChannel):object{

        if($this->getPaymentAPIStatus() === 'NOT-OK'){ return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server']; }

        $nowPaymentDetails = $this->createInvoice($pendingPaymentObject->amount, $pendingPaymentObject->description, $pendingPaymentObject->unique_id);
        if(empty((array) $nowPaymentDetails)){ return (object)['status'=>false, 'message'=>'Error connecting to Nowpayment server']; }

        $pendingPaymentObject->payment_option = $selectedPaymentChannel;
        $pendingPaymentObject->status = 'pending';
        $pendingPaymentObject->reference = $nowPaymentDetails->payment_id;
        $pendingPaymentObject->hosted_url = $nowPaymentDetails->url;
        $pendingPaymentObject->save();

        return (object) [
            'status'=>true,
            'data'=>$pendingPaymentObject
        ];
    }

    //confirm the payment
    function confirmNowPaymentCharges($payload, $paymentDataFromDb, $paymentModalInstance){

        if($paymentDataFromDb->status === $paymentModalInstance->paymentModalConfirmedStatus || $paymentDataFromDb->status === $paymentModalInstance->paymentModalRetryTransferStatus){//if status is confirmed jump the iteration
            return (object)['status'=>false, 'message'=>'Referenced data have been confirmed before'];
        }

        $paymentStatus = $payload['payment_status'];//get the status o this particular payment

        if($paymentStatus === 'finished'){
            //update the payment as confirmed
            $paymentDataFromDb->status = $paymentModalInstance->paymentModalConfirmedStatus;
            $paymentDataFromDb->save();

            //call the function for transafer to the user involved
            $userModel = $this->returnAppUserModel();
            $user_object = $userModel::where('unique_id', $paymentDataFromDb->user_unique_id)->first();
            $tranferDetails = $this->settleUser($paymentDataFromDb, $user_object);
            $this->settleReferrer($user_object, $paymentDataFromDb);

            return (object)['status'=>true, 'message'=>'User was successfully settled'];
        }else if($paymentStatus === 'expired'){
            $paymentDataFromDb->status = $paymentModalInstance->paymentModalExpiredStatus;
            $paymentDataFromDb->save();
            return (object)['status'=>true, 'message'=>'Payment has expired'];
        }else{
            return (object)['status'=>false, 'message'=>'Payment is yet to be confirmed'];
        }
    }

    function returnNowPaymentIpnSecret(){
        $env = $this->returnAppEnviroment();
        $key = $env === 'local' ? '' : '';
        $keyword = $env === 'local' ? 'NOW_PAYMENT_KEY_IPN_KEY_LOCAL' : 'NOW_PAYMENT_KEY_IPN_KEY_PRODUCTION';
        return env($keyword, $key);
    }

    function check_ipn_request_is_valid($incomingRequest)
    {
        $secret = $this->returnNowPaymentIpnSecret();
        //return ['status'=>false, 'message'=>$secret, 'data'=>$secret];
        $error_msg = "Unknown error";
        $auth_ok = false;
        $request_data = null;
        if (isset($_SERVER['HTTP_X_NOWPAYMENTS_SIG']) && !empty($_SERVER['HTTP_X_NOWPAYMENTS_SIG'])) {
            $recived_hmac = $_SERVER['HTTP_X_NOWPAYMENTS_SIG'];
            //$request_json = file_get_contents('php://input');
            $request_json = $incomingRequest;
            //$request_data = json_decode($request_json, true);
            $request_data = $request_json;
            ksort($request_data);
            $sorted_request_json = json_encode($request_data);

            if ($request_json !== false && !empty($request_json)) {
                $hmac = hash_hmac("sha512", $sorted_request_json, trim($secret));
                if ($hmac == $recived_hmac) {
                    $auth_ok = true;
                    return ['status'=>true, 'data'=>$request_data];
                } else {
                    $error_msg = 'HMAC signature does not match';
                    return ['status'=>false, 'message'=>'HMAC signature does not match', 'data'=>$request_data];
                }
            } else {
                $error_msg = 'Error reading POST data';
                return ['status'=>false, 'message'=>'Error reading POST data', 'data'=>$request_data];
            }

        } else {
            $error_msg = 'No HMAC signature sent.';
            return ['status'=>false, 'message'=>'No HMAC signature sent.', 'data'=>$request_data];
        }
    }




}