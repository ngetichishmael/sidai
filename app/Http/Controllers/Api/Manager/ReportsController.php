<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\customer\checkin;
use App\Models\Orders;
use App\Models\User;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

   public function reports(Request $request)
   {

      //Active Users
      $checking = checkin::select('user_code')
         ->groupBy('user_code');
      $all = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')
         ->today()
         ->groupBy('user_code');
      $today = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')
         ->yesterday()
         ->groupBy('user_code');
      $yesterday = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')
         ->currentWeek()
         ->groupBy('user_code');
      $this_week = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $lchecking = checkin::select('user_code')
         ->lastWeek()
         ->groupBy('user_code');
      $last_week = User::joinSub($lchecking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();

      $checking = checkin::select('user_code')
         ->currentMonth()
         ->groupBy('user_code');
      $month = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $lmchecking = checkin::select('user_code')
         ->lastMonth()
         ->groupBy('user_code');
      $last_month = User::joinSub($lmchecking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();

      (object)$data = [
         'status' => 200,
         'success' => true,
         "data" => (object)[
            'van_sales' =>
               [
                  'today' => Orders::where('order_type', 'Van sales')->today()->sum('price_total'),
                  'yesterday' => Orders::where('order_type', 'Van sales')->yesterday()->sum('price_total'),
                  'this_week' => Orders::where('order_type', 'Van sales')->currentWeek()->sum('price_total'),
                  'last_week' => Orders::where('order_type', 'Van sales')->lastWeek()->sum('price_total'),
                  'this_month' => Orders::where('order_type', 'Van sales')->currentMonth()->sum('price_total'),
                  'last_month' => Orders::where('order_type', 'Van sales')->lastMonth()->sum('price_total'),

               ],
            'pre_orders' => [
               'today' => Orders::where('order_type', 'Pre Order')->today()->sum('price_total'),
               'yesterday' => Orders::where('order_type', 'Pre Order')->yesterday()->sum('price_total'),
               'this_week' => Orders::where('order_type', 'Pre Order')->currentWeek()->sum('price_total'),
               'last_week' => Orders::where('order_type', 'Pre Order')->lastWeek()->sum('price_total'),
               'this_month' => Orders::where('order_type', 'Pre Order')->currentMonth()->sum('price_total'),
               'last_month' => Orders::where('order_type', 'Pre Order')->lastMonth()->sum('price_total'),

            ],
            'order_fulfillment' => [
               'today' => Orders::where('order_status', 'DELIVERED')->today()->sum('price_total'),
               'yesterday' => Orders::where('order_status', 'DELIVERED')->yesterday()->sum('price_total'),
               'this_week' => Orders::where('order_status', 'DELIVERED')->currentWeek()->sum('price_total'),
               'last_week' => Orders::where('order_type', 'DELIVERED')->lastWeek()->sum('price_total'),
               'this_month' => Orders::where('order_type', 'DELIVERED')->currentMonth()->sum('price_total'),
               'last_month' => Orders::where('order_type', 'DELIVERED')->lastMonth()->sum('price_total'),

            ],
            'active_users' => [
               'today' => $today,
               'yesterday' => $yesterday,
               'this_week' => $this_week,
               'last_week' => $last_week,
               'this_month' => $month,
               'last_month' => $last_month,
               "user_count" => $all,

            ],
            'customers_visits' => [
               'today' => checkin::select('customer_id', 'updated_at')->today()->groupBy('customer_id')->count(),
               'yesterday' => checkin::select('customer_id', 'updated_at')->yesterday()->groupBy('customer_id')->count(),
               'this_week' => checkin::select('customer_id', 'updated_at')->currentWeek()->groupBy('customer_id')->count(),
               'last_week' => checkin::select('customer_id', 'updated_at')->lastWeek()->groupBy('customer_id')->count(),
               'this_month' => checkin::select('customer_id', 'updated_at')->currentMonth()->groupBy('customer_id')->count(),
               'last_month' => checkin::select('customer_id', 'updated_at')->lastMonth()->groupBy('customer_id')->count(),

            ],
         ]
      ];
      return response()->json($data, 200);
   }
   public function vanSalesToday()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Van Sales for Today",
         'data' => Orders::where('order_type', 'Van sales')->today()->get(),
      ]);
   }
   public function vanSalesWeek()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Van Sales for last week",
         'data' => Orders::where('order_type', 'Van sales')->lastWeek()->get(),
      ]);
   }
   public function vanSalesMonth()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Van Sales for last month",
         'data' => Orders::where('order_type', 'Van sales')->lastMonth()->get(),
      ]);
   }
   public function preOrderToday()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Preorder for today",
         'data' => Orders::where('order_type', 'Pre Order')->with(['OrderItems',
            'Customer'=>function ($query) { $query->select('id','customer_name', 'user_code');
            }])->today()->get(),
      ]);
   }
   public function preOrderWeek()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Preorder for last week",
         'data' => Orders::where('order_type', 'Pre Order')->with(['OrderItems',
            'Customer'=>function ($query) { $query->select('id','customer_name', 'user_code');
            }])->lastWeek()->get(),
      ]);
   }
   public function preOrderMonth()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Preorder for last month",
         'data' => Orders::where('order_type', 'Pre Order')->with(['OrderItems',
            'Customer'=>function ($query) { $query->select('id','customer_name', 'user_code');
            }])->lastMonth()->get()
      ]);
   }
   public function orderFulfillmentToday()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Order fulfillment for today",
         'data' => Orders::where('order_type', 'DELIVERED')->today()->get(),
      ]);
   }
   public function orderFulfillmentWeek()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Order fulfillment for last week",
         'data' => Orders::where('order_type', 'DELIVERED')->lastWeek()->get(),
      ]);
   }
   public function orderFulfillmentMonth()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Order fulfillment for last month",
         'data' => Orders::where('order_type', 'DELIVERED')->lastMonth()->get(),
      ]);
   }
   public function visitsToday()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Customer Visits for today",
         "data" => checkin::with(['user', 'customer'])->select('customer_id', 'updated_at')->today()->groupBy('customer_id')->get(),
      ]);
   }
   public function visitsWeek()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Customer Visits for last week",
         "data" => checkin::with(['user', 'customer'])->select('customer_id', 'updated_at')->lastWeek()->groupBy('customer_id')->get(),
      ]);
   }
   public function visitsMonth()
   {
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Customer Visits for last month",
         "data" => checkin::with(['user', 'customer'])->select('customer_id', 'updated_at')->lastMonth()->groupBy('customer_id')->get(),
      ]);
   }
   public function activeUsersToday()
   {
      $checking = checkin::select('user_code')
         ->today()
         ->groupBy('user_code');
      $today = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->get();
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Active users for today",
         "data" => $today,
      ]);
   }
   public function activeUsersWeek()
   {
      $lchecking = checkin::select('user_code')
         ->lastWeek()
         ->groupBy('user_code');
      $last_week = User::joinSub($lchecking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->get();
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Active users for last week",
         "data" => $last_week,
      ]);
   }
   public function activeUsersMonth()
   {
      $lmchecking = checkin::select('user_code')
         ->lastMonth()
         ->groupBy('user_code');
      $last_month = User::joinSub($lmchecking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->get();
      return response()->json([
         'status' => 200,
         'success' => true,
         "message" => "Active users for last month",
         "data" => $last_month,
      ]);
   }
}
