<?php

namespace App\Http\Controllers\NoAuth;

use App\Models\User;
use App\Traits\Generics;
use App\Models\PaymentModal;
use Illuminate\Http\Request;
use App\Models\UserBankDetails;
use App\Models\CoinConversionRate;
use App\Traits\BeneficiariesTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Traits\Payments\FlutterWaveTrait;
use Illuminate\Support\Facades\Validator;

class ManageNoAuthPayments extends Controller
{
    use Generics, FlutterWaveTrait, BeneficiariesTrait;

    function __construct(CoinConversionRate $coinConversionRate, User $user, PaymentModal $paymentModal, UserBankDetails $userBankDetails)
    {
        $this->coinConversionRate = $coinConversionRate;
        $this->user = $user;
        $this->paymentModal = $paymentModal;
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    protected function validator(array $data)
    {
        //{amount:amount.value, coin_list:coin_list.value, bank_list:bank_list.value, account_number:account_number.value, account_name:account_name.value, phone_number:phone_number.value, email:email.value};
        $validator =  Validator::make($data, [
            'amount' => ['required', 'numeric'],
            'coin_list' => ['required', 'string', 'min:3'],
            'bank_list' => ['required', 'numeric', 'min:3'],
            'account_number' => ['required', 'numeric', 'min:3'],
            'account_name' => ['required', 'string', 'min:3'],
            'phone_number' => ['required', 'numeric', 'min:3'],
            'email' => ['required', 'email', 'min:5'],
        ]);

        return $validator;
    }

    public function store(Request $request)
    {
        try{
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return response()->json([ 'status'=>false, 'request_password'=>false, 'message'=>$validate->getMessageBag(), 'data'=>[] ]);
            }

            //check if the email is already in the database
            $checkEmailEistence = $this->user::where('email', $request->email)->first();
            if($checkEmailEistence === null){
                $password = $this->createNewUniqueId('users', 'unique_id', 10, 'alnum');
                $uniqueId = $this->createNewUniqueId('users', 'unique_id', 20, 'alnum');
                $userObject = $this->createUserAccount($request, $password, $uniqueId);
                $this->addNewBeneficiaries($request, $userObject);//add a bank account to flutterwave
                $this->sendRegistrationDetails($userObject, $password);//send an email bearing user credentials
                //create a login auth
                $userLogindetails = $this->createUserAuth($request, $password);
                if($userLogindetails->status === false){ throw new \Exception($userLogindetails->message); }
            }

            if($checkEmailEistence !== null){
                //request password from user
                return response()->json(['status'=>true, 'request_password'=>true, 'message'=>'Please provide Account Password', 'data'=>[] ]);
            }
            //call nowpayment API
            $paymentModaldetails = $this->processPayment($request, $userLogindetails->data['user']);
            if($paymentModaldetails->status === true){
                return response()->json([
                    'status'=>true,
                    'message'=>$paymentModaldetails->message,
                    'request_password'=>false,
                    'data'=>$paymentModaldetails->data
                ]);
            }

        }catch(\Exception $exception){
            return response()->json([
                'status'=>false,
                'message'=>['general_error'=>[$exception->getMessage()]],
                'request_password'=>false,
                'data'=>[]
            ]);
        }
    }

    function addNewBeneficiaries($request, $userObject){
        $BeneficiariesDetails = $this->addBeneficiaries($request->account_number, $request->bank_list, $request->account_name);
        if($BeneficiariesDetails->status === true){
            //get the bank accounts belonging to this user and update all their status to in-active
            $this->updateUserBankDetailsStatus($this->userBankDetails, $userObject);

            //save the beneficiar details
            $uniqueId = $this->createNewUniqueId('user_bank_details', 'unique_id', 20);
            $objectToSave = (object)[
                'unique_id'=>$uniqueId, 'account_number'=>$BeneficiariesDetails->data->account_number,
                'user_unique_id'=>$userObject->unique_id, 'beneficiary_name'=>$BeneficiariesDetails->data->full_name,
                'bank_name'=>$BeneficiariesDetails->data->bank_name, 'beneficiary_id'=>$BeneficiariesDetails->data->id,
                'status'=>$this->userBankDetails->activeStatus, 'bank_code'=>$BeneficiariesDetails->data->bank_code
            ];
            $this->addNewUserBankDetail($objectToSave, $this->userBankDetails, $uniqueId);
        }
    }

    function processPayment($request, $userObject){
        //call nowpayment API
            $paymentUniqueId = $this->createNewUniqueId('payment_modals', 'unique_id', 20);
            $paymentDetails = $this->createPayment($request->amount, $request->coin_list.' exchange', $paymentUniqueId, $request->coin_list);
            if($paymentDetails->status === false){ return (object)['status'=>false, 'message'=>$paymentDetails->message]; }

            //get the currency rate in local currency
            $coinConversionRateObject = $this->coinConversionRate::where('unique_id', $request->coin_list)->first();
            if($coinConversionRateObject === null){ return (object)['status'=>false, 'message'=>'Coinversion rate for the selected coin is currently not available']; }

            //add values to the database
            $paymentModaldetails = $this->savePaymentDetails($paymentDetails, $userObject, $request, $coinConversionRateObject, $this->paymentModal);
            if($paymentModaldetails){
                return (object)[
                    'status'=>true,
                    'message'=>'Payment details have been successfully returned',
                    'data'=>['url'=>route('payment-invoice', [$paymentModaldetails->unique_id])]
                ];
            }
            return (object)['status'=>false, 'message'=>'Payment initialization failed'];
    }


    function authenticateAndStorePayment(Request $request){
        try{
            $validate = $this->validator($request->all());
            if($validate->fails()){
                return response()->json([ 'status'=>false, 'message'=>$validate->getMessageBag(), 'data'=>[] ]);
            }

            //check if the email is already in the database
            $checkEmailEistence = $this->user::where('email', $request->email)->first();
            if($checkEmailEistence !== null){
                //create a login auth
                $userLogindetails = $this->createUserAuth($request, $request->password);
                if($userLogindetails->status === false){ throw new \Exception($userLogindetails->message); }
            }

            $this->addNewBeneficiaries($request, $userLogindetails->data['user']);
            $paymentModaldetails = $this->processPayment($request, $userLogindetails->data['user']);
            if($paymentModaldetails->status === false){ throw new \Exception($paymentModaldetails->message); }
            return response()->json([
                'status'=>true,
                'message'=>$paymentModaldetails->message,
                'data'=>$paymentModaldetails->data
            ]);


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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}