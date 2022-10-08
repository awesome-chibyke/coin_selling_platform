<?php

namespace App\Http\Controllers\AccountActivation;

use Exception;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Settings;
use App\Traits\Generics;
use Illuminate\Http\Request;
use App\Mail\AccountActivationMail;
use App\Traits\AuthenticationTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Traits\AccountActivationTrait;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AccountActivationController extends Controller
{
    use AuthenticationTrait, Generics, AccountActivationTrait;

    function __construct(User $user, Settings $settings){
        $this->user = $user;
        $this->settings = $settings;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userId)
    {

        return view('auth.account_activation', ['user_id'=>$userId]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function resendActivationCode($userId)
    {
        try{

            //select the user involved
            $user = $this->user::where('unique_id', $userId)->first();
            if($user === null){ throw new Exception('User details does not exist');}

            //send account activtion mail
            $resendCodeDetail = $this->forwardActivationMail($this->accountActivationType, $user);
            if($resendCodeDetail === false){  throw new Exception('An activation code could not be sent at this time. Please try again');}

            return Redirect::back()->with('status', 'Account activation code has been resent to your mail');

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    protected function validator(array $data)
    {
        $validator =  Validator::make($data, [
            'code' => ['required', 'numeric', 'min:6'],
        ]);

        return $validator;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{
            //user_unique_id code
            $this->validator($request->all())->validate();

            //select the user involved
            $user = $this->user::where('unique_id', $request->user_unique_id)->first();
            if($user === null){ throw new Exception('User details does not exist');}

            $code = $request->code;

            //check if the token is valid
            $tokenStatus = $this->authenticateCode($this->accountActivationType, $user, $code, 'yes');
            if($tokenStatus->status === false){ throw new Exception($tokenStatus->message); }

            //update the user
            $user->email_verified_at = Carbon::now()->toDateTimeString();
            if($user->save()){//send success account activation mail
                $this->sendAccountActivationSuccessMail($user, $this->settings);

                return redirect()->route('login')->with('status', 'Your account has been activated, please provide your detials to login');
            }
        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
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
