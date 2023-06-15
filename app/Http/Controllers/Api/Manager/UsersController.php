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
         $user = User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])
            ->where('region_id', '=', $request->user()->region_id)
            ->with(["TargetSales" => function ($query) {
               $query->toJson();
            }, "TargetLeads" => function ($query) {
               $query->toJson();
            }, "TargetsOrder" => function ($query) {
               $query->toJson();
            }, "TargetsVisit" => function ($query) {
               $query->toJson();
            }])
            ->get();
      } else {
         $user = User::whereIn('account_type', ['TSR', 'TD', 'Shop-Attendee'])
            ->with(["TargetSales" => function ($query) {
               $query->toJson();
            }, "TargetLeads" => function ($query) {
               $query->toJson();
            }, "TargetsOrder" => function ($query) {
               $query->toJson();
            }, "TargetsVisit" => function ($query) {
               $query->toJson();
            }])
            ->get();
      }

      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $user,
      ]);
   }
}
