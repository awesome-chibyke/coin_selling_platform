<?php

namespace App\Traits\CoinConversionRate;

trait CoinConversionRateTrait{

    public $localCurrency = 'NGN';

    function returnLocalCurrency(){
        return $this->localCurrency;
    }

    function saveCoinConversionRate($coinConversionRateModelInstance, $request){
        $coinConversionRate = $coinConversionRateModelInstance;
        $coinConversionRate->unique_id = $request->coin_code;
        $coinConversionRate->rate_in_local_currency = $request->rate_in_local_currency;
        $coinConversionRate->local_currency = $this->returnLocalCurrency();
        $coinConversionRate->equi_in_dollar = 1;
        $coinConversionRate->coin_name = $request->coin_name;
        $coinConversionRate->save();
        return $coinConversionRate;
    }

    function updateCoinConversionRate($coinConversionRateObject, $request){
        $coinConversionRateObject->rate_in_local_currency = $request->rate_in_local_currency;
        $coinConversionRateObject->local_currency = $this->returnLocalCurrency();
        $coinConversionRateObject->coin_name = $request->coin_name;
        $coinConversionRateObject->save();
        return $coinConversionRateObject;
    }

    function combineConversionRateAndCoinData(array $coinsForPaymentArray, object $coinCoversionRateArray):array{

        $newArrayOfAvailableCoinForPayment = []; $selectArrayOfCoinConversionRate = []; $arrayToReturn = [];

        if(count($coinsForPaymentArray) >0){
            foreach($coinsForPaymentArray as $k => $eachCoinForPayment){
                $eachCoinForPayment->local_currency_rate = null;
                $eachCoinForPayment->local_currency = $this->returnLocalCurrency();
                $newArrayOfAvailableCoinForPayment[strtolower($eachCoinForPayment->code)] = $eachCoinForPayment;
            }
            $arrayToReturn = $newArrayOfAvailableCoinForPayment;
        }

        count($coinCoversionRateArray) > 0 ?
            $arrayToReturn = $this->compareCoinRateAndAvalaibleCoinForPayment($coinCoversionRateArray, $newArrayOfAvailableCoinForPayment) : $arrayToReturn;

        return $arrayToReturn;
    }

    private function compareCoinRateAndAvalaibleCoinForPayment($coinCoversionRateArray, $newArrayOfAvailableCoinForPayment){
        $arrayToReturn = [];
        //also recreate the coin array to have the coin code as keys
        foreach($coinCoversionRateArray as $k => $eachCoinCoversionRate){
            $selectArrayOfCoinConversionRate[strtolower($eachCoinCoversionRate->unique_id)] = $eachCoinCoversionRate;
        }

        //compare the two arays and create a new array that contains both object
        foreach($newArrayOfAvailableCoinForPayment as $k => $eachArrayOfAvailableCoinForPayment){
            if (array_key_exists($k, $selectArrayOfCoinConversionRate)){
                $eachArrayOfAvailableCoinForPayment->local_currency_rate = $selectArrayOfCoinConversionRate[$k]->rate_in_local_currency;
                $eachArrayOfAvailableCoinForPayment->local_currency = $selectArrayOfCoinConversionRate[$k]->local_currency;
                $arrayToReturn[$k] = $eachArrayOfAvailableCoinForPayment;
            }else{
                $eachArrayOfAvailableCoinForPayment->local_currency_rate = null;
                $eachArrayOfAvailableCoinForPayment->local_currency = $this->returnLocalCurrency();
                $arrayToReturn[$k] = $eachArrayOfAvailableCoinForPayment;
            }
        }
        return $arrayToReturn;
    }

}