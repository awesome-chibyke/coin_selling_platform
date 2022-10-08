<?php
namespace App\Traits;

use App\Models\AuthenticationCode;
use Carbon\Carbon;

trait AuthenticationTrait{

    public $secConstant = 'sec';
    public $minConstant = 'min';
    public $hourConstant = 'hr';
    public $daysConstant = 'days';

    public $unUsedCodeStatus = 'un-used';
    public $usedCodeStatus = 'used';
    public $cancelledCodeStatus = 'cancelled';

    public $accountActivationType = 'account-activation';
    public $passwordResetType = 'password-reset';

    function returnAuthenticationModelInstance(){
        return new AuthenticationCode();
    }

    function createAuthenticationCode(string $type, object $user, string $uniqueId, string $time = null):object{

        $AuthenticationModel = $this->returnAuthenticationModelInstance();

        //check if the code for the tpe has been created before and un-used
        $checkExistence = $AuthenticationModel::where([
            ['type', '=', $type],
            ['status', '=', $this->unUsedCodeStatus],
            ['user_unique_id', '=', $user->unique_id]
        ])->first();

        if($checkExistence !== null){
            $checkExistence->status = $this->cancelledCodeStatus;
            $checkExistence->save();
        }

        //create and save the newly created code
        $code = $this->randomCode('numeric', 6);
        $AuthenticationModel->unique_id = $uniqueId;
        $AuthenticationModel->code = $code;
        $AuthenticationModel->type = $type;
        $AuthenticationModel->user_unique_id = $user->unique_id;
        $AuthenticationModel->status = $this->unUsedCodeStatus;
        if($time !== null){//add a timer if the time for expiration is sent in
            $AuthenticationModel->expiration_time = $this->returnExpirationTime($time);
        }
        $AuthenticationModel->save();

        //create new code and send out
        return (object)['status'=>true, 'message'=>'Token was successfully created', 'data'=>['code'=>$code] ];

    }

    function authenticateCode(string $type, object $user, int $code, $updateToUsedStatus = 'no'):object{

        $AuthenticationModel = $this->returnAuthenticationModelInstance();

        //check if the code for the tpe has been created before and un-used
        $checkExistence = $AuthenticationModel::where([
            ['type', '=', $type],
            ['status', '=', $this->unUsedCodeStatus],
            ['code', '=', $code],
            ['user_unique_id', '=', $user->unique_id]
        ])->first();
        if($checkExistence === null){//check if the selected code eist
            return (object)['status'=>false, 'message'=>'Invalid Token Supplied'];
        }

        if($checkExistence->expiration_time !== null){//check if a timer was set
            if(Carbon::now()->toDateTimeString() > Carbon::parse($checkExistence->expiration_time)->toDateTimeString()){
                $checkExistence->status = $this->cancelledCodeStatus;
                $checkExistence->save();//update the status to cancelled
                return (object)['status'=>false, 'message'=>'Supplied Token has expired'];
            }
        }

        if($updateToUsedStatus === 'no'){
            return (object)['status'=>true, 'message'=>'Supplied Token is Valid'];
        }else if($updateToUsedStatus === 'yes'){
            $checkExistence->status = $this->usedCodeStatus;
            $checkExistence->save();
            return (object)['status'=>true, 'message'=>'Supplied Token is Valid'];
        }

    }

    function randomCode ( $type = 'alnum', $len = 60 )
    {
        switch ( $type )
        {
            case 'alnum'	:
            case 'numeric'	:
            case 'nozero'	:

                switch ($type)
                {
                    case 'alnum'	:	$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        break;
                    case 'numeric'	:	$pool = '0123456789';
                        break;
                    case 'nozero'	:	$pool = '123456789';
                        break;
                }

                $str = '';
                for ( $i=0; $i < $len; $i++ )
                {
                    $str .= substr ( $pool, mt_rand ( 0, strlen ( $pool ) -1 ), 1 );
                }
                return $str;
                break;
            case 'unique' : return md5 ( uniqid ( mt_rand () ) );
                break;
        }
    }

    function returnExpirationTime($time){
        $explodedTime = explode(' ', $time);
        if(count($explodedTime) != 2){
            return ['status'=>false, 'message'=>'incorrect time format'];
        }

        if($explodedTime[1] === $this->secConstant){
            return Carbon::now()->addSeconds($explodedTime[0])->toDateTimeString();
        }else if($explodedTime[1] === $this->minConstant){
            return Carbon::now()->addMinutes($explodedTime[0])->toDateTimeString();
        }else if($explodedTime[1] === $this->hourConstant){
            return Carbon::now()->addHours($explodedTime[0])->toDateTimeString();
        }else if($explodedTime[1] === $this->daysConstant){
            return Carbon::now()->addDays($explodedTime[0])->toDateTimeString();
        }
    }

}

// public $unUsedCodeStatus = 'un-used';
//     public $usedCodeStatus = 'used';
//     public $cancelledCodeStatus = 'cancelled';
