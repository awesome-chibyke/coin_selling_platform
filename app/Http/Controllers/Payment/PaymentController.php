<?php

namespace App\Http\Controllers\Payment;

use App\Models\User;
use App\Models\Referal;
use App\Traits\Generics;
use App\Models\PaymentModal;
use Illuminate\Http\Request;
use App\Models\CoinConversionRate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\CoinMarketDetailsUpdate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Events\FlutterWaveTransferConfirmation;

class PaymentController extends Controller
{
    use Generics;
    function __construct(CoinMarketDetailsUpdate $coinMarketDetailsUpdate, PaymentModal $paymentModal, CoinConversionRate $coinConversionRate, User $user, Referal $referal)
    {
        $this->coinMarketDetailsUpdate = $coinMarketDetailsUpdate;
        $this->paymentModal = $paymentModal;
        $this->coinConversionRate = $coinConversionRate;
        $this->user = $user;
        $this->referal = $referal;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCoinValue($amount, $toCurrency)
    {
        try{

            $currencyDetails = $this->getEstimatedAmount($amount, $toCurrency);
            if($currencyDetails->status === false){throw new \Exception($currencyDetails->message);}

            //return the data for processing at the front end
            return response()->json([
                'status'=>true,
                'message'=>'Currenc value was returned successfully',
                'data'=>$currencyDetails->data
            ]);

        }catch(\Exception $exception){
            return response()->json([
                'status'=>false,
                'message'=>$exception->getMessage()
            ]);
        }
    }

    /**
     * display the coins available for payment.
     *
     * @return \Illuminate\Http\Response
     */
    public function displayCoinsAvailableForPayment()
    {
        $coins_for_payment = $this->coinMarketDetailsUpdate::where('id', 1)->first();

        return view('logged.deposit_coin', ['coins_for_payment'=>json_decode($coins_for_payment->content), 'image_base_url'=>$this->nowPaymentMainPath, 'coinMarketInstance'=>$this->coinMarketDetailsUpdate]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function validator(array $data)
    {
        $validator =  Validator::make($data, [
            'price_amount' => ['required', 'numeric'],
            'coin' => ['required', 'string', 'min:2'],
        ]);

        return $validator;
    }

    public function initializePayment(Request $request)
    {

        try{
            $userObject = Auth()->user();

            $validate = $this->validator($request->all());
            if($validate->fails()){
                return response()->json([ 'status'=>false, 'message'=>$validate->getMessageBag(), 'data'=>[] ]);
            }

            $uniqueId = $this->createNewUniqueId('payment_modals', 'unique_id', 20);
            $paymentDetails = $this->createPayment($request->price_amount, $request->coin.' exchange', $uniqueId, $request->coin);

            if($paymentDetails->status === false){ throw new \Exception($paymentDetails->message); }

            //get the currency rate in local currency
            $coinConversionRateObject = $this->coinConversionRate::where('unique_id', $request->coin)->first();
            if($coinConversionRateObject === null){ throw new \Exception('Coinversion rate for the selected coin is currently not available'); }

            //add values to the database
            $paymentModaldetails = $this->savePaymentDetails($paymentDetails, $userObject, $request, $coinConversionRateObject, $this->paymentModal);
            if($paymentModaldetails){
                return response()->json([
                    'status'=>true,
                    'message'=>'Payment details have been successfully returned',
                    'data'=>['url'=>route('payment-invoice', [$paymentModaldetails->unique_id])]
                ]);
            }

        }catch(\Exception $exception){
            return response()->json([
                'status'=>false,
                'message'=>['general_error'=>[$exception->getMessage()]],
                'data'=>[]
            ]);
        }
    }

/**
* Display the specified resource.
*
* @param int $id
* @return \Illuminate\Http\Response
*/
public function paymentInvoice($invoiceId)
{
    try{

        $paymentDetails = $this->paymentModal::where('unique_id', $invoiceId)->first();
        if($paymentDetails->status === false){ throw new \Exception($paymentDetails->message); }

        return view('logged.payment_invoice', ['payment_details'=>$paymentDetails]);

    }catch(\Exception $exception){
        return Redirect::back()->with('error', $exception->getMessage());
    }
}

/**
* Show the form for editing the specified resource.
*
* @param int $id
* @return \Illuminate\Http\Response
*/
public function paymentHistory($type_of_transaction, $paymentStatus = null, $startDate = null, $endDate = null)
{
    $loggedInUser = Auth()->user();
    if($loggedInUser->type_of_user === $this->user->normalUserType){

        $payments = $paymentStatus === null ?
        $this->paymentModal::orderBy('id', 'DESC')
        ->where('action_type', $type_of_transaction)
        ->where('user_unique_id', $loggedInUser->unique_id)->get()
        : $this->paymentModal::where('status', $paymentStatus)
        ->where('action_type', $type_of_transaction)
        ->where('user_unique_id', $loggedInUser->unique_id)
        ->orderBy('id', 'DESC')->get();

        //filter by date_create_from_format
        $retunedOject = $this->filterByDate($loggedInUser, $paymentStatus, $startDate, $endDate, $type_of_transaction);

        $payments = empty((array)$retunedOject) ? $payments : $retunedOject;

    }else{
        $payments = $paymentStatus === null ? $this->paymentModal::where('action_type', $type_of_transaction)
        ->orderBy('id', 'DESC')->get()
        : $this->paymentModal::where('status', $paymentStatus)
        ->where('action_type', $type_of_transaction)
        ->orderBy('id', 'DESC')->get();
        //filter b date
        $retunedOject = $this->filterByDate($loggedInUser, $paymentStatus, $startDate, $endDate, $type_of_transaction);
        $payments = empty((array)$retunedOject) ? $payments : $retunedOject;
    }

    return view('logged.payment_history', ['payments'=>$payments, 'payment_modal_instance'=>$this->paymentModal]);
}

//filter bey date
private function filterByDate($loggedInUser, $paymentStatus, $startDate, $endDate, $type_of_transaction){

    if($startDate !==  null &&  $endDate !== null){
        if($loggedInUser->type_of_user === $this->user->normalUserType){

            $payments = $paymentStatus === null ?
            $this->paymentModal::where('user_unique_id', $loggedInUser->unique_id)
            ->where('action_type', $type_of_transaction)
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '>=', $endDate)
            ->where('action_type', $this->paymentModal->coinSaleActionType)->get()
            : $this->paymentModal::where('status', $paymentStatus)
            ->where('action_type', $type_of_transaction)
            ->where('user_unique_id', $loggedInUser->unique_id)->where('created_at', '>=', $startDate)
            ->where('created_at', '>=', $endDate)
            ->where('action_type', $this->paymentModal->coinSaleActionType)
            ->orderBy('id', 'DESC')->get();

        }else{

            $payments = $paymentStatus === null ? $this->paymentModal::where('created_at', '>=', $startDate)
            ->where('action_type', $type_of_transaction)
            ->where('created_at', '>=', $endDate)
            ->where('user_unique_id', $loggedInUser->unique_id)
            ->orderBy('id', 'DESC')->get()
            : $this->paymentModal::where('created_at', '>=', $startDate)
            ->where('created_at', '>=', $endDate)->where('status', $paymentStatus)
            ->where('user_unique_id', $loggedInUser->unique_id)
            ->where('action_type', $this->paymentModal->coinSaleActionType)
            ->orderBy('id', 'DESC')->get();

        }
        return $payments;
    }
    return (object)[];
}
/**
* Update the specified resource in storage.
*
* @param \Illuminate\Http\Request $request
* @param int $id
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $id)
{
    //
}

/**
* Remove the specified resource from storage.
*
* @param int $id
* @return \Illuminate\Http\Response
*/
public function destroy(Request $request)
{
    try{
        $selected_payments = $request->selected_payments;
        $deleteStatus = 0;
        if(count($selected_payments)){
            foreach($selected_payments as $k => $eachPayment){
                $paymentObject = $this->paymentModal::where('unique_id', $eachPayment)->first();

                if($paymentObject !==  null){
                    $settlementObject = $this->paymentModal::where('deposit_transaction_id', $paymentObject->unique_id)->first();
                    if($settlementObject !==  null){ $settlementObject->delete(); }
                    if($paymentObject->delete()) { $deleteStatus++; }
                }
            }
            if($deleteStatus > 0){
                return response()->json([
                    'status'=>true,
                    'message'=>'Selected Payments were successfully deleted',
                    'data'=>[]
                ]);
            }
        }
    }catch(\Exception $exception){
        return response()->json([
                'status'=>false,
                'message'=>['general_error'=>[$exception->getMessage()]],
                'data'=>[]
            ]);
    }
}

//confirms payment from nopayment
public function confirmNowPaymentTansaction(Request $request)
    {

        try{
            $incomingRequest = $request->input();
            //Storage::put('public/now_payment/roles.json', json_encode($request->input()));

            $incomingRequestDetails = $this->check_ipn_request_is_valid($incomingRequest);
            if($incomingRequestDetails['status'] === false){ throw new \Exception($incomingRequestDetails['message']); }

            //save to file
            //Storage::put('payments/payments.json', json_encode($request->input()));
            //get the unique code for this payment
            $uniqueCodeForPayment = $incomingRequest['payment_id'];
            $order_id = $incomingRequest['order_id'];

            //get the payment object from the db
            $paymentDataFromDb = $this->paymentModal::where('unique_id', $order_id)->where('status', $this->paymentModal->paymentModalPendingStatus)->first();
            if($paymentDataFromDb === null){
                throw new \Exception('Referenced data does not exist');
            }

            $confirmNowPaymentCharges = $this->confirmNowPaymentCharges($incomingRequest, $paymentDataFromDb, $this->paymentModal);
            if($confirmNowPaymentCharges->status === false){
                throw new \Exception($confirmNowPaymentCharges->message);
            }
            return response('success', 200);

        }catch(\Exception $exception){
            return response($exception->getMessage(), 401);
        }

    }

    public function confirmFlutterWaveTransfer(Request $request){
        // If you specified a secret hash, check for the signature
        $secretHash = $this->returnWebhookSecret();
        $signature = $request->header('verif-hash');

        if (!$signature || ($signature !== $secretHash)) {
            // This request isn't from Flutterwave; discard
            abort(401);
        }
        $payload = $request->all();
        //Storage::put('public/now_payment/roles.json', json_encode($payload)); die();
        // It's a good idea to log all received events.
        Log::info($payload);
        // Do something (that doesn't take too long) with the payload
        if($payload['event.type'] === 'Transfer'){

            $allReferalRecords = [];

            $idForSelection = explode('-', $payload['transfer']['reference']);
            $transferObjectFromDb = $this->paymentModal::where('unique_id', $idForSelection[0])->where('status', $this->paymentModal->paymentModalProcessingTransfer)->first();

            if(count($idForSelection) == 1){
                $transferObjectFromDb === null ? abort(401) : '';

                $transferConfirmationObject = $this->completeFlutterWaveTransferConfirmation($payload['transfer'], $transferObjectFromDb, $this->paymentModal);
                $transferConfirmationObject->status === false ? abort(401) : '';
                return response(200);

            }

            if(count($idForSelection) == 2){

                $allReferalRecords = $this->referal::where('transfer_batch_id', $idForSelection[1])->get();
                $transferConfirmationObject = $this->completeFlutterWaveReferalTransferConfirmation($payload['transfer'], $transferObjectFromDb, $this->paymentModal, $allReferalRecords, $this->referal);

                $transferConfirmationObject->status === false ? abort(401) : '';
                return response(200);

            }


        }

    }
}
