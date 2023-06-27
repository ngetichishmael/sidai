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
   public function getCustomers(Request $request)
   {
      if ($request->user()->account_type ==='RSM') {
         $customers = customers::withCount('number_visited')->with(['orders.orderItems'])
            ->where('region_id', Auth::user()->region_id)
            ->get();
      }
      $customers = customers::withCount('number_visited')->with(['orders.orderItems'])
         ->get();
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $customers,
      ]);
   }

}
