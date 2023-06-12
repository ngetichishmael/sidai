<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\customer\checkin;
use App\Models\customer\customers;
use App\Models\inventory\allocations;
use App\Models\Orders;
use App\Models\survey\survey;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardAppController extends Controller
{

   public function dashboard(Request $request)
   {
      //Active Users
      $checking = checkin::select('user_code')->groupBy('user_code');
      $all = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
//      $checking = checkin::select('user_code')->today()->groupBy('user_code');
      $checking = checkin::select('user_code')->whereDate('created_at', now()->toDateString())->groupBy('user_code')->get();
      $today = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')->yesterday()->groupBy('user_code');
      $yesterday = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')->currentWeek()->groupBy('user_code');
      $this_week = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')->currentMonth()->groupBy('user_code');
      $month = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();

      $data = [
         'status' => 200,
         'success' => true,
         'active_users' => [
            'today' => $today,
            'yesterday' => $yesterday,
            'this_week' => $this_week,
            'month' => $month,
            "user_count" => $all,
         ],
         'new_customers_visits' => [
//            'today' => checkin::select('customer_id', 'updated_at')->today()->groupBy('customer_id')->count(),
            'today' => checkin::select('customer_id', 'updated_at')->whereDate('updated_at', now())->groupBy('customer_id')->count(),
            'yesterday' => checkin::select('customer_id', 'updated_at')->yesterday()->groupBy('customer_id')->count(),
            'this_week' => checkin::select('customer_id', 'updated_at')->currentWeek()->groupBy('customer_id')->count(),
            'month' => checkin::select('customer_id', 'updated_at')->currentMonth()->groupBy('customer_id')->count(),
         ],
         'new_customers_added' => [
            'today' => customers::today()->count(),
            'yesterday' => customers::yesterday()->count(),
            'this_week' => customers::currentWeek()->count(),
            'month' => customers::currentMonth()->count(),
         ],
         'pre_sales_value' => [
            'today' => Orders::where('order_type', 'Pre Order')->today()->count(),
            'yesterday' => Orders::where('order_type', 'Pre Order')->yesterday()->count(),
            'this_week' => Orders::where('order_type', 'Pre Order')->currentWeek()->count(),
            'month' => Orders::where('order_type', 'Pre Order')->currentMonth()->count(),
         ],
         'existing_customer_visit' => [
            'today' => customers::today()->count(),
            'yesterday' => customers::yesterday()->count(),
            'this_week' => customers::currentWeek()->count(),
            'month' => customers::currentMonth()->count(),
         ],
         'pending_approval' => allocations::where('status', 'Waiting acceptance')->count(),
         'completed_forms' => survey::where('status', 'Completed')->count(),

         'custom_data' => $this->custom($request)->getData(),
      ];
      return response()->json($data, 200);
   }
   public function custom(Request $request)
   {
      $start_date = $request->start_date;
      $end_date = $request->end_date;
      $checking = checkin::select('user_code')->period($start_date, $end_date)->groupBy('user_code');
      $today = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $data = [
//         'status' => 200,
//         'success' => true,
         'active_users' => $today,
         'new_customers_visits' => checkin::select('customer_id', 'updated_at')->period($start_date, $end_date)->groupBy('customer_id')->count(),
         'new_customers_added' =>  customers::period($start_date, $end_date)->count(),
         'pre_sales_value' => Orders::where('order_type', 'Pre Order')->period($start_date, $end_date)->count(),
         'existing_customer_visit' => customers::period($start_date, $end_date)->count(),
         'pending_approval' => allocations::where('status', 'Waiting acceptance')->period($start_date, $end_date)->count(),
         'completed_forms' => survey::where('status', 'Completed')->period($start_date, $end_date)->count(),
      ];
      return response()->json($data, 200);

   }
}
