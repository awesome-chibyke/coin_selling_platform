<?php

namespace App\Traits;

use App\Models\Settings;
use App\Models\CoinConversionRate;

trait FrontEndTraits{

    function mergeData(array $array = []):array{
        $siteDetails = $this->returnSiteDetails();
        return array_merge([
            'siteName' => $siteDetails->company_name,
            'siteEmail' => $siteDetails->email1,
            'sitePhone' => $siteDetails->phone1,
            'siteAddress' => $siteDetails->address1,
            'siteDomain' => $siteDetails->site_url,
            'siteURL' => $siteDetails->site_url,
            'settings'=>$siteDetails,
            'conversion_rate'=>$this->returnPriceRanges(),
            'fiat_rate'=>$this->returnFiatRate()
        ], $array);
    }

    function returnSettingsModel(){
        return new Settings();
    }

    function returnCoinConversionModel(){
        return new CoinConversionRate();
    }

    function returnSiteDetails(){
        $settingsModel = $this->returnSettingsModel();
        return $settingsModel::first();
    }

    function returnPriceRanges(){
        $coinConversionModel = $this->returnCoinConversionModel();

        return $coinConversionModel::orderBy('id', 'DESC')->get();
    }

    function returnFiatRate(){
        $coinConversionModel = $this->returnCoinConversionModel();
        return $coinConversionModel::where('unique_id', 'USDTTRC20')->first();
    }

    function getCoinDetails($coinMarketDetailsUpdateModel, $coinConversionRateModelInstance){
        $coinsForPayment = $coinMarketDetailsUpdateModel::where('id', 1)->first();
        if($coinsForPayment === null){ return (object)['status'=>false, 'message'=>'Coin Market detail is currently not available', 'data'=>[] ]; }

        $coinsForPaymentArray = json_decode($coinsForPayment->content);
        $coinCoversionRateArray = $coinConversionRateModelInstance::get();

        //generate a new arra that holds both the conversion rate and coin data
        $combinedCoinRateAndCoinForPaymentsArray = $this->combineConversionRateAndCoinData($coinsForPaymentArray, $coinCoversionRateArray);
        return (object)['status'=>false, 'message'=>'Success', 'data'=>['coin_rate_and_coin_for_payments_array'=>$combinedCoinRateAndCoinForPaymentsArray ] ];
    }

}
