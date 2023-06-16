<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TargetsController extends Controller
{
   public function getSalespersonTarget(Request $request)
   {
       $user_code=$request->user()->user_code;
       $target=User::with('countPendingOrders','TargetSales','TargetLeads','TargetsOrder','TargetsVisit')
       ->where('user_code',$user_code)->get();
       return response()->json([
           "success" => true,
           "message" => "Target Set",
           "Targets"=>$target,
       ]);
   }
}
