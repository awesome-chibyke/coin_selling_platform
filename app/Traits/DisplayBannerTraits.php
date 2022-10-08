<?php

namespace App\Traits;

trait DisplayBannerTraits{

    function deactivateDisplayBanner($displayBannerModelInstance){

        $displayBannerObject = $displayBannerModelInstance::where('status', $displayBannerModelInstance->activeBannerDisplayStatus)->first();
        if($displayBannerObject !== null){
            $displayBannerObject->status = $displayBannerModelInstance->InActiveBannerDisplayStatus;
            $displayBannerObject->save();
        }
    }

    function createDisplayBanner($request, $filename, $displayBannerModelInstance, $uniqueId){
        $displayBanner = $displayBannerModelInstance;
        $displayBanner->unique_id = $uniqueId;
        $displayBanner->title = $request->title;
        $displayBanner->description = $request->description;
        $displayBanner->filename = $filename;
        $displayBanner->status = $displayBannerModelInstance->activeBannerDisplayStatus;
        $displayBanner->save();
        return $displayBanner;
    }

    function updateDisplayBanner($request, $displayBannerDbObject){
        $displayBannerDbObject->title = $request->title ?? $displayBannerDbObject->title;
        $displayBannerDbObject->description = $request->description ?? $displayBannerDbObject->description;
        $displayBannerDbObject->filename = $request->filename ?? $displayBannerDbObject->filename;
        $displayBannerDbObject->status = $request->status ?? $displayBannerDbObject->status;
        $displayBannerDbObject->save();
        return $displayBannerDbObject;
    }

}