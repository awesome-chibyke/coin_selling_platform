<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;

trait ChangeGateayTrait{

    function returnChangeIoKey(){
        return env('CHANGE_GATEWAY', 'jrua4yft6gwcogs4goggcsco0c4004s4k4scwoo8sowsgcowsgw04wookggowosc');
    }

    function getUsdtBepToken(){
        $url = 'https://eu.bsc.chaingateway.io/v1/getToken';
        $contractAddress = '0x55d398326f99059ff775485246999027b3197955';
        $key = $this->returnChangeIoKey();

        $response = Http::withHeaders([
            'Authorization'=>$key,
            'Content-Type'=>'application/json'
        ])->post($url, ["contractaddress"=> $contractAddress]);

        return $response;
    }

}