<?php

namespace App\Helpers;

class SMS
{
   public function __invoke($phone_number, $message)
   {
      $curl = curl_init();

      $url = 'https://accounts.jambopay.com/auth/token';
      curl_setopt($curl, CURLOPT_URL, $url);
      curl_setopt($curl, CURLOPT_HTTPHEADER, array(
         'Content-Type: application/x-www-form-urlencoded',
      ));
      curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query([
         'grant_type' => 'client_credentials',
         'client_id' => config('custom.jambopay.sms_client_id'),
         'client_secret' => config('custom.jambopay.sms_client_secret')
      ]));
      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $curl_response = curl_exec($curl);

      $token = json_decode($curl_response);
      curl_close($curl);

      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://swift.jambopay.co.ke/api/public/send',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => json_encode([
            "sender_name" => "SOKOFLOW",
            "contact" => $phone_number,
            "message" => $message,
            "callback" => "https://pasanda.com/sms/callback"
         ]),
         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token->access_token
         ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
   }
}
