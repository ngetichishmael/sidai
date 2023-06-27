<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\customer\customers;
use App\Models\Delivery;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;

class DeliveriesController extends Controller
{
   public function getDeliveries(Request $request)
   {
      $user_code = $request->user()->user_code;
      info('user_code: ' . $user_code);
      $deliveries = Delivery::with(['DeliveryItems', 'Customer','Order.distributor', 'Order.customer'])
         ->where('allocated', '=', $user_code)
         ->get();
      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "All Deliveries with their orders",
         "deliveries" => $deliveries,
      ]);
   }
   public function getManagersDeliveries(Request $request)
   {
      $region_id = $request->user()->region_id;
      $customer_code = $request->customer_code;

      $customer = customers::where('user_code', $customer_code)->first();
      if ($customer) {
         $customer_id = $customer->id;
      } else {
         $customer = User::where('user_code', $customer_code)->first();
         if ($customer) {
            $customer_id = customers::where('phone_number', $customer->phone_number)->value('id');
         } else {
            return response()->json([
               "success" => false,
               "status" => 409,
               "Message" => "Customer Not Found, Something went wrong!!",
            ]);
         }
      }

         if ($request->user()->account_type === 'RSM') {
            $deliveries = Delivery::with(['DeliveryItems', 'Customer', 'Order.distributor', 'Order.customer'])
               ->whereHas('Customer', function ($query) use ($customer_id) {
                  $query->where('id', $customer_id);
               })
               ->whereHas('Customer.Area.Subregion.Region', function ($query) use ($region_id) {
                  $query->where('id', $region_id);
               })
               ->get();
         } else {
            $deliveries = Delivery::with(['DeliveryItems', 'Customer', 'Order.distributor', 'Order.customer'])
               ->whereHas('Customer', function ($query) use ($customer_id) {
                  $query->where('id', $customer_id);
               })
               ->get();
         }

         return response()->json([
            "success" => true,
            "status" => 200,
            "Message" => "All Deliveries with their orders for customer",
            "deliveries" => $deliveries,
         ]);
      }
   public function getManagersCustomDeliveries(Request $request)
   {
      $region_id = $request->user()->region_id;
      $customer_code = $request->customer_code;
      $start_date = $request->start_date;
      $end_date = $request->end_date;

      $customer = customers::where('user_code', $customer_code)->first();
      if ($customer) {
         $customer_id = $customer->id;
      } else {
         $customer = User::where('user_code', $customer_code)->first();
         if ($customer) {
            $customer_id = customers::where('phone_number', $customer->phone_number)->value('id');
         } else {
            return response()->json([
               "success" => false,
               "status" => 409,
               "Message" => "Customer Not Found, Something went wrong!!",
            ]);
         }
      }

      $query = Delivery::with(['DeliveryItems', 'Customer', 'Order.distributor', 'Order.customer'])
         ->whereHas('Customer', function ($query) use ($customer_id) {
            $query->where('id', $customer_id);
         });

      if ($request->user()->account_type === 'RSM') {
         $query->whereHas('Customer.Area.Subregion.Region', function ($query) use ($region_id) {
            $query->where('id', $region_id);
         });
      }

      if ($start_date && $end_date) {
         $query->whereBetween('updated_at', [$start_date, $end_date]);
      }

      $deliveries = $query->get();

      return response()->json([
         "success" => true,
         "status" => 200,
         "Message" => "All Deliveries with their orders for the customer",
         "deliveries" => $deliveries,
      ]);
   }
   }
