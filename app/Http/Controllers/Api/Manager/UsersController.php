<?php

namespace App\Http\Controllers\api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
//   public function getUsers(Request $request)
//   {
//      if ($request->account_type == 'RSM') {
//         $users = User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])->where('region_id', '=', $request->user()->region_id)->with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")->get();
//      }
//      else{
//         $users = User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])->with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")->get();
//      }
//      $transformedUsers = $users->transform(function ($user) {
//         $user->target_sales = (object) $user->target_sales;
//         $user->target_leads = (object) $user->target_leads;
//         $user->targets_order = (object) $user->targets_order;
//         $user->targets_visit = (object) $user->targets_visit;
//         return $user;
//      });
//      return response()->json([
//         "success" => true,
//         "status" => 200,
//         "data" => $transformedUsers,
//      ]);
//   }

   public function getUsers(Request $request)
   {
      if ($request->account_type == 'RSM') {
         $users = UserResource::collection(
            User::withCount('Customers')->with(
               [
                  'TargetSales', 'TargetLeads', 'TargetsOrder', 'TargetsVisit'
               ]
            )->whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])->where('region_id', '=', $request->user()->region_id)->get()
         );
      }else{
         $users = UserResource::collection(
            User::withCount('Customers')->with(
               [
                  'TargetSales', 'TargetLeads', 'TargetsOrder', 'TargetsVisit'
               ]
            )->whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])->get()
         );
      }
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $users,
      ]);
   }
   public function usersList(Request $request)
   {
      if ($request->account_type == 'RSM' || 'rsm') {
         $users =
            User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])
               ->where('region_id', $request->user()->region_id)
               ->pluck('name', 'user_code','account_type');
         return response()->json([
            "success" => true,
            "status" => 200,
            "data" => $users,
         ]);
      }else if ($request->account_type == 'NMS' || 'nsm'){
         $users =
            User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])
               ->pluck('name', 'user_code', 'account_type');
         return response()->json([
            "success" => true,
            "status" => 200,
            "data" => $users,
         ]);
      }
      return response()->json([
         "success" => false,
         "status" => 401,
         "data" => "UNAUTHORIZED USER!!!",
      ]);
   }
   public function suspendUser(Request $request)
   {
      $suspension = User::where('user_code', $request->user_code)->update([
         'status' => 'suspended',
      ]);
      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "User suspended successfully",
         "suspension" => $suspension,
      ]);
   }
   public function activateUser(Request $request)
   {
      $suspension = User::where('user_code', $request->user_code)->update([
         'status' => 'Active',
      ]);
      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "User activated successfully",
         "suspension" => $suspension,
      ]);
   }
}
