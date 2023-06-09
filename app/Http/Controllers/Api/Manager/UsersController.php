<?php

namespace App\Http\Controllers\api\Manager;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\suppliers\suppliers;
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
      $accountType = $request->input('account_type');

      if ($request->account_type == 'RSM' || 'rsm') {

         $users =User::where('account_type',$accountType )->whereNotIn('account_type', ['Customer', 'Admin'])->where('status', 'Active')->where('region_id', $request->user()->region_id)
               ->select('name', 'user_code','account_type')->get();
         return response()->json([
            "success" => true,
            "status" => 200,
            "data" => $users,
         ]);
      }else if ($request->account_type == 'NMS' || 'nsm'){
         $users = User::where('account_type',$accountType )->whereNotIn('account_type', ['Customer', 'Admin'])->where('status', 'Active')->select('name', 'user_code', 'account_type')->get();
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
   public function accountTypes()
   {
      $account_types = User::whereNotIn('account_type', ['Customer', 'Admin'])
         ->select('account_type')
         ->groupBy('account_type')
         ->get()
         ->pluck('account_type')
         ->toArray();

      $account_types[] = 'Distributors';
         return response()->json([
            "success" => true,
            "status" => 200,
      "account_types" => $account_types,

         ]);
   }
   public function distributors()
   {
      $distributors = suppliers::whereRaw('LOWER(name) NOT IN (?, ?)', ['sidai', 'sidai'])->whereIn('status', ['Active', 'active'])
      ->orWhereNull('status')
      ->orWhere('status', '')
      ->orderby('name', 'desc')->get();
      return response()->json([
            "success" => true,
            "status" => 200,
      "distributors" => $distributors,

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
