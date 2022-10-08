<?php

namespace App\Http\Controllers\Beneficiaries;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\UserBankDetails;
use App\Traits\BeneficiariesTrait;
use App\Http\Controllers\Controller;
use App\Models\BankDetails;
use Illuminate\Support\Facades\Redirect;
use App\Traits\Payments\FlutterWaveTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;

class BeneficiariesController extends Controller
{
    use FlutterWaveTrait, BeneficiariesTrait;

    function __construct(UserBankDetails $userBankDetails, User $user, BankDetails $bankDetails)
    {
        $this->userBankDetails = $userBankDetails;
        $this->user = $user;
        $this->bankDetails = $bankDetails;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($userUniqueId = null)
    {
        $user_object = $this->user::where('unique_id', $userUniqueId)->first();

        $userBankArray = ($userUniqueId === null) ? $this->userBankDetails::orderBy('id', 'DESC')->paginate(10) : $this->userBankDetails::where('user_unique_id', $userUniqueId)->paginate(10);

        $myPaginationLinks = $this->myPagination($userBankArray, '/view-bank?page=');

        return view('logged.user_bank_list', ['user_banks'=>$userBankArray, 'user_object'=>$user_object, 'my_pagination_links'=>$myPaginationLinks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($userUniqueId = null)
    {
        $user_object = $userUniqueId === null ? Auth()->user() : $this->user::where('unique_id', $userUniqueId)->first();
        $loggedInUser = Auth()->user();

        $bankArray = $this->bankDetails::get();//get all the bank list

        $userBankArray = Auth()->user()->type_of_user === $this->user->normalUserType ? $this->userBankDetails::where('user_unique_id', $loggedInUser->unique_id)->get() : $this->userBankDetails::orderBy('id', 'DESC')->get();

        $userBankArray = $userUniqueId !== null ? $this->userBankDetails::where('user_unique_id', $userUniqueId)->get() : $userBankArray;

        return view('logged.create-bank', ['bank_array'=>$bankArray, 'user_object'=>$user_object, 'user_banks'=>$userBankArray]);
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
            'bank_code' => ['required', 'string', 'min:3'],
            'account_number' => ['required', 'string', 'min:10'],
            'beneficiary_name' => ['required', 'string', 'max:255']
        ]);

        return $validator;
    }

    public function store(Request $request, $userUniqueId)
    {
        try{
            $validate = $this->validator($request->all());
            if($validate->fails()){ return Redirect::back()->withErrors($validate->getMessageBag()); }

            $userObject = $this->user::where('unique_id', $userUniqueId)->first();

            $BeneficiariesDetails = $this->addBeneficiaries($request->account_number, $request->bank_code, $request->beneficiary_name);
            if($BeneficiariesDetails->status === false){throw new \Exception($BeneficiariesDetails->message);}

            //get the bank accounts belonging to this user and update all their status to in-active
            $this->updateUserBankDetailsStatus($this->userBankDetails, $userObject);

            //save the beneficiar details
            $uniqueId = $this->createNewUniqueId('user_bank_details', 'unique_id', 20);
            $objectToSave = (object)[
                'unique_id'=>$uniqueId, 'account_number'=>$request->account_number,
                'user_unique_id'=>$userObject->unique_id, 'beneficiary_name'=>$request->beneficiary_name,
                'bank_name'=>$BeneficiariesDetails->data->bank_name, 'beneficiary_id'=>$BeneficiariesDetails->data->id,
                'status'=>$this->userBankDetails->activeStatus, 'bank_code'=>$BeneficiariesDetails->data->bank_code
            ];
            $newBankDetails = $this->addNewUserBankDetail($objectToSave, $this->userBankDetails, $uniqueId);

            if($newBankDetails){
                return Redirect::back()->with('success', 'Bank Account was updated successfully');
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
    protected function validateDataForUserBankVerification(array $data)
    {
        $validator =  Validator::make($data, [
            'bank_code' => ['required', 'string', 'min:3'],
            'account_number' => ['required', 'string', 'min:10'],
        ]);

        return $validator;
    }
    public function validateAccountDetails(Request $request)
    {
        try{
            $validate = $this->validateDataForUserBankVerification($request->all());
            if($validate->fails()){
                return response()->json([
                    'status'=>false,
                    'message'=>$validate->getMessageBag(),
                    'data'=>[]
                ]);
            }

            $bankVericationDetails = $this->nubanVerify($request->account_number, $request->bank_code);
            if(isset($bankVericationDetails->error) && $bankVericationDetails->error === true){
                throw new \Exception($bankVericationDetails->message);
            }

            //send the response to the front end
            return response()->json([
                'status'=>true,
                'message'=>'Account details was validated successfully',
                'data'=>$bankVericationDetails[0]
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{

            $bankDetail = $this->userBankDetails::where('unique_id', $id)->first();
            if($bankDetail === null){ throw new \Exception('Selected Bank does not exist'); }

            if($bankDetail->delete()){
                return Redirect::back()->with('success', 'Bank was deleted successfully');
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }

    //activate a particular bank address
    function activate($uniqueId){

        try{

            $bankObject = $this->userBankDetails::where('unique_id', $uniqueId)->first();
            $userObject = $bankObject->current_user_object;

            //get the bank accounts belonging to this user and update all their status to in-active
            $this->updateUserBankDetailsStatus($this->userBankDetails, $userObject, $uniqueId);

            //Alert::success('Success', 'Activation of selected bank account was successful');
            return Redirect::back()->with('success', 'Activation of selected bank account was successful');



        }catch(\Exception $exception){
            Alert::error('Error', $exception->getMessage());
            return Redirect::back()->with('error', $exception->getMessage());
        }

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
        try{

            $bankDetail = $this->userBankDetails::where('unique_id', $id)->first();
            if($bankDetail === null){ throw new \Exception('Selected Bank does not exist'); }

            //delete from the flutter wave
            $deleteOperation = $this->deleteABeneficiary($bankDetail->beneficiary_id);
            if($deleteOperation->status === false){ throw new \Exception($deleteOperation->message); }

            if($bankDetail->delete()){
                return Redirect::back()->with('success', 'Selected Bank was deleted successfully');
            }

        }catch(\Exception $exception){
            return Redirect::back()->with('error', $exception->getMessage());
        }
    }
}