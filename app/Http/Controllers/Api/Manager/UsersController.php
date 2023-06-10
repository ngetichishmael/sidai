<?php

namespace App\Http\Controllers\api\manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
   public function getUsers()
   {
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => User::with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")
            ->whereIn('account_type', ['TSR','TD'])->where('region_id', auth()->user()->region_id)->get(),
      ]);
   }
}
