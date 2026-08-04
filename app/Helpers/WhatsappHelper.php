<?php

namespace App\Helpers;

use Twilio\Rest\Client;
use Config;

class WhatsappHelper
{
    static function whatsappSend($mobile_numbers){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => Config::get('services.whatsapp.url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>'{
                "messaging_product": "whatsapp",
                "recipient_type": "individual",
                "to": "'.$mobile_numbers.'",
                "type": "template",
                "template": { "name": "hello_world", "language": { "code": "en_US" } }
            }',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer EAAIkx1nXLuoBANQwPZC2DqJ04AutQvSCZAGCUV8Dtx2QRiakRypbWU6ZCZC5lnIgJ3OphgHB2qf3p5nxGLCRge94GIa9zCYK3gtLpeaUz5VtbqoaBTUhZCbh6Ba2E8UveowZBtp80eE0fLkOfAF0gcv3Da0jxZCaU1H9dXu0lUOLHVc8zApTq4MsZCPPoJdNsrVn4iF2Anendj4r8HZCWeZBYf',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }

    static function twilio(){
        // Find your Account SID and Auth Token at twilio.com/console
        // and set the environment variables. See http://twil.io/secure
        // $sid = getenv("TWILIO_ACCOUNT_SID");
        // $token = getenv("TWILIO_AUTH_TOKEN");
        $sid = "AC7bc8d004897248cdc2dfec40c737d82f";
        $token = "433b83912865b4d0fdaaae6fa6ea5517";
        $twilio = new Client($sid, $token);
        $test = "name";

        $message = $twilio->messages
        ->create("whatsapp:+96176311718",
           [
               "from" => "whatsapp:+14155238886",
               "body" => "Hello $test there!"
           ]
       );

        print($message->sid);
    }
}