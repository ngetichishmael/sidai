<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Delivery;

class TestingController extends Controller
{
   public function test()
   {
      $deliveries = Delivery::with(
         [
            'DeliveryItems:id',
            'Customer:customer_name', 'Order:id'
         ]
      )
         ->get();
      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "All Deliveries with their orders",
         "deliveries" => $deliveries,
      ]);
   }
}
