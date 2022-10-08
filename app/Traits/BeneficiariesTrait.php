<?php

namespace App\Traits;

trait BeneficiariesTrait{

    function updateUserBankDetailsStatus(object $userBankDetailsModelInstance, object $userObject, string $uniqueId = null): void{
        $bankDetails = $userBankDetailsModelInstance::where('user_unique_id', $userObject->unique_id)->get();
        if(count($bankDetails) > 0){
            foreach($bankDetails as $k => $eachBankDetails){
                if($uniqueId === $eachBankDetails->unique_id){
                    $eachBankDetails->status = $userBankDetailsModelInstance->activeStatus;
                }else{
                    $eachBankDetails->status = $userBankDetailsModelInstance->notActiveStatus;
                }

                $eachBankDetails->save();
            }
        }
    }

    function addNewUserBankDetail(object $request, object $userBankDetailsModelInstance, string $uniqueId): object{
        $userBankDetails = $userBankDetailsModelInstance;
        $userBankDetails->unique_id = $request->unique_id;
        $userBankDetails->user_unique_id = $request->user_unique_id;
        $userBankDetails->account_number = $request->account_number;
        $userBankDetails->bank_code = $request->bank_code;
        $userBankDetails->bank_name = $request->bank_name;
        $userBankDetails->beneficiary_name = $request->beneficiary_name;
        $userBankDetails->beneficiary_id = $request->beneficiary_id;
        $userBankDetails->status = $request->status;
        $userBankDetails->save();

        return $userBankDetails;
    }
}