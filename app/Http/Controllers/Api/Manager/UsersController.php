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
      return response()->json([
         "success" => true,
         "status" => 200,
         "id"=>$request->user()->region_id,
         "data" => User::where('region_id','=', 2)->with("TargetSales", "TargetLeads", "TargetsOrder", "TargetsVisit")->get(),
      ]);
   }
}
