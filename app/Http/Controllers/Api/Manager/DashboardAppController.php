<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\customer\checkin;
use App\Models\customer\customers;
use App\Models\inventory\allocations;
use App\Models\Orders;
use App\Models\survey\survey;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardAppController extends Controller
{
   public function dashboard(Request $request)
   {

      //Active Users
      $checking = checkin::select('user_code')->groupBy('user_code');
      $all = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')->today()->groupBy('user_code');
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
      $checking = checkin::select('user_code')->lastWeek()->groupBy('user_code');
      $last_week = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')->currentMonth()->groupBy('user_code');
      $month = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $checking = checkin::select('user_code')->lastMonth()->groupBy('user_code');
      $last_month = User::joinSub($checking, 'customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code');
      })->count();
      $data = [
         'status' => 200,
         'success' => true,
         'active_users' => [
            'today' => $today,
            'yesterday' => $yesterday,
            'this_week' => $this_week,
            'last_week' => $last_week,
            'month' => $month,
            'last_month' => $last_month,
            "user_count" => $all,
         ],
         'new_customers_visits' => [
            'today' => checkin::select('customer_id', 'updated_at')->today()->groupBy('customer_id')->count(),
            'yesterday' => checkin::select('customer_id', 'updated_at')->yesterday()->groupBy('customer_id')->count(),
            'this_week' => checkin::select('customer_id', 'updated_at')->currentWeek()->groupBy('customer_id')->count(),
            'last_week' => checkin::select('customer_id', 'updated_at')->lastWeek()->groupBy('customer_id')->count(),
            'month' => checkin::select('customer_id', 'updated_at')->currentMonth()->groupBy('customer_id')->count(),
            'last_month' => checkin::select('customer_id', 'updated_at')->lastMonth()->groupBy('customer_id')->count(),
         ],
         'new_customers_added' => [
            'today' => customers::today()->count(),
            'yesterday' => customers::yesterday()->count(),
            'this_week' => customers::currentWeek()->count(),
            'last_week' => customers::lastWeek()->count(),
            'month' => customers::currentMonth()->count(),
            'last_month' => customers::lastMonth()->count(),
         ],
         'pre_sales_value' => [
            'today' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->today()->count(),
            'yesterday' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->yesterday()->count(),
            'this_week' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->currentWeek()->count(),
            'last_week' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->lastWeek()->count(),
            'month' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->currentMonth()->count(),
            'last_month' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->lastMonth()->count(),
         ],
         'van_sales_value' => [
            'today' => Orders::where('order_type', 'Van Sales')->whereIn('supplierID', [1, '', null])->today()->count(),
            'yesterday' => Orders::where('order_type', 'Van Sales')->whereIn('supplierID', [1, '', null])->yesterday()->count(),
            'this_week' => Orders::where('order_type', 'Van Sales')->whereIn('supplierID', [1, '', null])->currentWeek()->count(),
            'last_week' => Orders::where('order_type', 'Van Sales')->whereIn('supplierID', [1, '', null])->lastWeek()->count(),
            'month' => Orders::where('order_type', 'Van Sales')->whereIn('supplierID', [1, '', null])->currentMonth()->count(),
            'last_month' => Orders::where('order_type', 'Van Sales')->whereIn('supplierID', [1, '', null])->lastMonth()->count(),
         ],
         'distributor_orders' => [
            'today' => Orders::where(function ($query) {
               $query->where('supplierID', '!=', 1)
                  ->orWhereNotNull('supplierID')
                  ->orWhereNot('supplierID', '');
            })->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])->today()->count(),
            'yesterday' => Orders::where(function ($query) {
               $query->where('supplierID', '!=', 1)
                  ->orWhereNotNull('supplierID')
                  ->orWhereNot('supplierID', '');
            })->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])->yesterday()->count(),
            'this_week' => Orders::where(function ($query) {
               $query->where('supplierID', '!=', 1)
                  ->orWhereNotNull('supplierID')
                  ->orWhereNot('supplierID', '');
            })->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])->currentWeek()->count(),
            'last_week' => Orders::where(function ($query) {
               $query->where('supplierID', '!=', 1)
                  ->orWhereNotNull('supplierID')
                  ->orWhereNot('supplierID', '');
            })->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])->lastWeek()->count(),
            'month' => Orders::where(function ($query) {
               $query->where('supplierID', '!=', 1)
                  ->orWhereNotNull('supplierID')
                  ->orWhereNot('supplierID', '');
            })->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])->currentMonth()->count(),
            'last_month' => Orders::where(function ($query) {
               $query->where('supplierID', '!=', 1)
                  ->orWhereNotNull('supplierID')
                  ->orWhereNot('supplierID', '');
            })->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])->lastMonth()->count(),
         ],
         'existing_customer_visit' => [
            'today' => customers::today()->count(),
            'yesterday' => customers::yesterday()->count(),
            'this_week' => customers::currentWeek()->count(),
            'last_week' => customers::lastWeek()->count(),
            'month' => customers::currentMonth()->count(),
            'last_month' => customers::lastMonth()->count(),
         ],
         'pending_approval' => allocations::where('status', 'Waiting acceptance')->count(),
         'completed_forms' => survey::where('status', 'Completed')->count(),

         'custom_data' => $this->custom($request)->getData(),
      ];
      $action="Viewed Managers Dashboard ";
      $activity="Viewed Managers Dashboard on Mobile App";
      $this->activitylogs($action, $activity);
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
         'new_customers_visits' => checkin::select('customer_id', 'created_at')->period($start_date, $end_date)->groupBy('customer_id')->count(),
         'new_customers_added' =>  customers::period($start_date, $end_date)->count(),
         'pre_sales_value' => Orders::where('order_type', 'Pre Order')->whereIn('supplierID', [1, '', null])->period($start_date, $end_date)->count(),
         'van_sales_value' => Orders::where('order_type', 'Van sales')->whereIn('supplierID', [1, '', null])->period($start_date, $end_date)->count(),
         'distributor_orders' => Orders::where(function ($query) {
            $query->where('supplierID', '!=', 1)
               ->orWhereNotNull('supplierID')
               ->orWhereNot('supplierID', '');
         })->where('order_type', 'Pre Order')->period($start_date, $end_date)->count(),
         'existing_customer_visit' => customers::period($start_date, $end_date)->count(),
         'pending_approval' => allocations::where('status', 'Waiting acceptance')->period($start_date, $end_date)->count(),
         'completed_forms' => survey::where('status', 'Completed')->period($start_date, $end_date)->count(),
      ];
      return response()->json($data, 200);

   }

   public function activitylogs($activity,$action): void
   {
      $rdm = Str::random(20);
      $activityLog = new activity_log();
      $activityLog->activity = $activity;
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Mobile';
      $activityLog->action =  $action;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $rdm;
      $activityLog->ip_address = session('login_ip');
      $activityLog->save();
   }
}
