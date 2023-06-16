<?php

namespace App\Http\Controllers\api\manager;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
   public function getCustomers2()
   {
      $customers = customers::with(['number_visited','orders.orderItems'])
         ->where('region_id', Auth::user()->region_id)
         ->get();

      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $customers,
      ]);

   }
   public function getCustomers()
   {
      $customers = customers::with(['number_visited', 'orders.orderItems'])
         ->where('region_id', Auth::user()->region_id)
         ->get();
      $transformedCustomers = $customers->transform(function ($customer) {
         $customer->number_visited = $customer->number_visited->pluck('count')->first();
         return $customer;
      });

      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $transformedCustomers,
      ]);
   }

}
