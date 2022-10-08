<?php

namespace App\Traits;

trait SettingsTrait{

    function updateSettings($app_settings, $request){
        $app_settings->company_name = $request->company_name ?? $app_settings->company_name;
        $app_settings->email1 = $request->email1 ?? $app_settings->email1;
        $app_settings->email2 = $request->email2 ?? $app_settings->email2;
        $app_settings->phone1 = $request->phone1 ?? $app_settings->phone1;
        $app_settings->phone2 = $request->phone2 ?? $app_settings->phone2;
        $app_settings->address1 = $request->address1 ?? $app_settings->address1;
        $app_settings->address2 = $request->address2 ?? $app_settings->address2;
        $app_settings->linkedin = $request->linkedin ?? $app_settings->linkedin;
        $app_settings->twitter = $request->twitter ?? $app_settings->twitter;
        $app_settings->facebook = $request->facebook ?? $app_settings->facebook;
        $app_settings->instagram = $request->instagram ?? $app_settings->instagram;
        $app_settings->site_url = $request->site_url ?? $app_settings->site_url;
        $app_settings->slogan = $request->slogan ?? $app_settings->slogan;
        $app_settings->logo_url = $request->logo_url ?? $app_settings->logo_url;
        $app_settings->least_referal_amount = $request->least_referal_amount ?? $app_settings->least_referal_amount;
        $app_settings->referal_percentage = $request->referal_percentage ?? $app_settings->referal_percentage;
        $app_settings->save();
        return $app_settings;
    }
}
