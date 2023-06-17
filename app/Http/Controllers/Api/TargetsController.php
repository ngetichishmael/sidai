<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class TargetsController extends Controller
{
    public function countPendingOrders(): int
    {
        return $this->PendingOrders()->where('order_type', 'Pre Order')->whereIn('order_status', ['Pending Derivery', 'Waiting acceptance'])->count();
    }
   public function getSalespersonTarget(Request $request)
   {
       $user_code=$request->user()->user_code;
       $target=User::with('PendingOrders','TargetSales','TargetLeads','TargetsOrder','TargetsVisit')
       ->where('user_code',$user_code)->get();

       $target->each(function ($item) {
           $item->pendingOrderCount = $item->countPendingOrders();
       });
       return response()->json([
           "success" => true,
           "message" => "Target Set",
           "Targets"=>$target,
       ]);
   }
}
