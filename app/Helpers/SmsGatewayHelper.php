<?php

namespace App\Helpers;
use Config;

class SmsGatewayHelper
{
    static function smsSend($mobile_numbers, $message){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => Config::get('services.sms.url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "Username": "'.Config::get('services.sms.username').'",
                "apiID": "'.Config::get('services.sms.apikey').'",
                "Password": "'.Config::get('services.sms.password').'",
                "destination": '.$mobile_numbers.',
                "source": "'.Config::get('services.sms.sender_id').'",
                "text": "'.$message.'"
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Basic '.Config::get('services.sms.authorization'),
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}