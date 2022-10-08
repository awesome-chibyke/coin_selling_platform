<?php

namespace App\Traits;

trait NubanVerify{

    public function nubanVerify($account_number = '', $bank_code = '')
    {
        $ch = curl_init();
        $query = http_build_query([
            'bank_code' => $bank_code,
            'acc_no' => $account_number
        ]);
        $url = "https://app.nuban.com.ng/api/NUBAN-IFQGEDVI173";
        $getUrl = $url . "?" . $query;
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $getUrl);
        curl_setopt($ch, CURLOPT_TIMEOUT, 80);

        $response = curl_exec($ch);

        if (curl_error($ch)) {
             echo 'Request Error:' . curl_error($ch);
            return false;
        } else {
            return json_decode($response);
        }

        curl_close($ch);
        //function ends here
    }

}