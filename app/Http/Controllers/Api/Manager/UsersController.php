<?php

namespace App\Http\Controllers\api\manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
   public function getUsers()
   {
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => User::with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")
            ->whereIn('account_type', ['TSR','TD', 'Shop-Attendee'])->where('region_id', Auth::user()->region_id)->get(),
      ]);
   }
}
