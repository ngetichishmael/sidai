<?php

namespace App\Http\Controllers\api\manager;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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
      $action="Getting customers";
      $activity="Getting customers using managers app";
      $this->activitylogs($action, $activity);
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $customers,
      ]);
   }
   public function getUnapprovedCustomers(Request $request)
   {
      if ($request->user()->account_type ==='RSM') {
         $customers = customers::where('approval','waiting_approval')->where('customer_type','normal')
            ->where('region_id', Auth::user()->region_id)
            ->get();
      }
      $customers = customers::where('approval','waiting_approval')->where('customer_type','normal')
         ->get();
      $action="Getting customers";
      $activity="Getting customers using managers app";
      $this->activitylogs($action, $activity);
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $customers,
      ]);
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
