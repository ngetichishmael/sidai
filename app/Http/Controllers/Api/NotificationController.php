<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
   public function sendFirebaseNotification()
   {

      $token = [
         "dNCXRn5ISZCH3LxStsbv6N:APA91bF9PQYSUYcBxFl3MhYRieB-8XnnojhU0t3QL89rLFydStIQPeMlNorWoGulScjpmZuhzes7ovE5w0pL7jhVq4MF5Km0rVIQGDi6eLtrk_gCFhxe2j_5MibRXER-eN7HkVMDSz03",
      ];
      $fcmUrl = 'https://fcm.googleapis.com/fcm/send';

      $fcmNotification = [
         'registration_ids'  => $token, //device token (smartphones unique identifier)
         'notification' => [
            'title' => "John", //notification title
            'body' => "Sumbua",
            'image' => "https://images.unsplash.com/photo-1669156130305-2104f8c246a6?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHx0b3BpYy1mZWVkfDR8UzRNS0xBc0JCNzR8fGVufDB8fHx8&auto=format&fit=crop&w=500&q=60"
         ],
         'data' => [
            "route" => '/notification'
         ]
      ];

      $headers = [
         'Authorization: key=AAAAF82SEcA:APA91bG8wzqRzTiPtl-IAVH6BvjFpAIjR23PWks_BAcclupXSZXE-f_YFISD-nfKCWpwym7G60EmH1oa1hScvreTtVAHrkH_BFiCpP66zvzTslZyXSCDgpiXaJVtv4gc2zKm-YC3wXvx', //firebase server key
         'Content-Type: application/json'
      ];

      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, $fcmUrl);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
      $result = curl_exec($ch);
      curl_close($ch);
      return response()->json([
         $result
      ]);
   }
   public function getCustomerNotification(Request $request)
   {
      $user_code = $request->user()->user_code;
      $notification = Notification::where("user_code", $user_code)->get();
      return response()->json([
         "status" => "success",
         "message" => "All Notifications",
         "data" => $notification
      ]);
   }
}
