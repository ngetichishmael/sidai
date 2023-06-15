<?php

namespace App\Http\Controllers\api\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
   public function getUsers(Request $request)
   {
      if ($request->account_type == 'RSM') {
         $users = User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])->where('region_id', '=', $request->user()->region_id)->with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")->get();
      }
      else{
         $users = User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])->with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")->get();
      }
      $transformedUsers = $users->transform(function ($user) {
         $user->target_sales = (object) $user->target_sales;
         $user->target_leads = (object) $user->target_leads;
         $user->targets_order = (object) $user->targets_order;
         $user->targets_visit = (object) $user->targets_visit;
         return $user;
      });
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $transformedUsers,
      ]);
   }
}
