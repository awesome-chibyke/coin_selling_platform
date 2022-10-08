<?php

namespace App\Http\Controllers\Settings;

use Exception;
use App\Models\Settings;
use App\Traits\Generics;
use App\Models\TypeOfGame;
use App\Models\AppSettings;
use App\Models\BankDetails;
use App\Traits\ErrorHelper;
use Illuminate\Http\Request;
use App\Models\TransactionModel;
use App\Models\PaymentGatewayBox;
use App\Models\CurrencyRatesModel;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AppSettingsController extends Controller
{

    use Generics;

    var $validator;

    function __construct(Settings $appSettings, BankDetails $bankDetails)
    {
        $this->middleware('auth');
        $this->appSettings = $appSettings;
        $this->bankDetails = $bankDetails;

    }

    protected function Validator($request){

        $this->validator = Validator::make($request->all(), [//site_name 	address1 	address2 	email1 	site_url
            'site_name'              => 'nullable|string',
            'address1'         => 'nullable|string',
            'address2'          => 'nullable|string',
            'email1'        => 'nullable|string',
            'site_url'          => 'nullable|string',
            'email2'            => 'nullable|string',
            'logo_url'            => 'nullable|string'
        ]);

    }

    function createAppSettings(Request $request){
        $create = $this->appSettings->insertIntoModel($request);

        if ($create){
            return  redirect('settings_page')->with('success_message', 'App Settings Was Successfully Created');
        }else{
            return  redirect('settings_page')->with('error_message', 'An error occurred, please try again');
        }

    }

    protected function handleValidation($request){

        $validator = Validator::make($request, [//site_name 	address1 	address2 	email1 	site_url
            'site_name'              => 'nullable|string',
            'slogan'              => 'nullable|string',
            'company_name'              => 'nullable|string',
            'address1'         => 'nullable|string',
            'address2'          => 'nullable|string',
            'email1'        => 'nullable|string',
            'site_url'          => 'nullable|string',
            'email2'            => 'nullable|string',
            'phone1'            => 'nullable|numeric',
            'phone2'            => 'nullable|numeric',
            'logo_url'            => 'nullable|string',
            'linkedin'            => 'nullable|url',
            'twitter'            => 'nullable|url',
            'facebook'            => 'nullable|url',
            'instagram'            => 'nullable|url',
        ]);
        return $validator;

    }

    function updateAppSettings(Request $request){

        try{
            $validation = $this->handleValidation($request->all());
            if($validation->fails()){
                return Redirect::back()->withErrors($validation->getMessageBag());
            }

            $app_settings = $this->appSettings::first();
            if($app_settings === null){ throw new \Exception('Settings does not exist'); }

            $updatedAppSettings = $this->updateSettings($app_settings, $request);

            if($updatedAppSettings){
                return Redirect::back()->with('success', 'Update was successful');
            }

        }catch (Exception $exception){
            $errorsArray = $exception->getMessage();
            return Redirect::back()->with('error', $errorsArray);

        }

    }


    function showAppSettings(){
        $appSettings = $this->appSettings::first();
        return view('logged.main_settings_page', ['appSetting'=>$appSettings]);
    }

    function getAccountDetails(){

        $accountDetails = $this->bankDetails->getAllBankdetails();
        return view('dashboard.bank_details', ['accountDetails'=>$accountDetails]);

    }//get the bank details for display

    function deletebankDetails(Request $request){//deleteBankDetails

        $BankUniqueId = $request->dataArray;
        $deleteStatus = 0;
        foreach($BankUniqueId as $eachID){
            $bankDetails = $this->bankDetails->getOneBankdetail($eachID);
            if($bankDetails !== null){
                $bankDetails->delete();
                $deleteStatus = 1;
            }
        }
        if($deleteStatus == 1){
            return response()->json(['error_code'=>0, 'success_statement'=>'selected Bank details have been deleted successfully']);
        }
        return response()->json(['error_code'=>1, 'error_statement'=>'An error occurred, transaction failed']);

    }

}