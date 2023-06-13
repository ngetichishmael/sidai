<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\LeadsTargets;
use App\Models\OrdersTarget;
use App\Models\SalesTarget;
use App\Models\User;
use App\Models\VisitsTarget;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TargetController extends Controller
{
   protected $lastDayofMonth;

   public function __construct()
   {
      $this->lastDayofMonth =  Carbon::parse(Carbon::now())->endOfMonth()->toDateString();;
   }

   public function assignVisitTarget(Request $request)
   {
      $validator           =  Validator::make($request->all(), [
         "user_code"   => "required",
         "target"   => "required",
      ]);
      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 401, "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }
      VisitsTarget::updateOrCreate(
         [
            'user_code' => $request->user_code,
            'Deadline' => $request->date ?? $this->lastDayofMonth,
         ],
         [
            'VisitsTarget' => $request->target,
            'bussiness_code' => $request->user()->business_code,
         ]
      );
      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Target assigned for the following user",
         "data" => User::find($request->user_code),
      ]);
   }
   public function assignLeadTarget(Request $request)
   {
      $validator           =  Validator::make($request->all(), [
         "user_code"   => "required",
         "target"   => "required",
      ]);
      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 401, "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }
      LeadsTargets::updateOrCreate(
         [
            'user_code' => $request->user_code,
            'Deadline' =>  $request->date ?? $this->lastDayofMonth,
         ],
         [
            'LeadsTarget' => $request->target,
            'bussiness_code' => $request->user()->business_code,
         ]
      );

      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Target assigned for the following users",
         "data" => User::find($request->user_code),
      ]);
   }
   public function assignOrderTarget(Request $request)
   {
      $validator           =  Validator::make($request->all(), [
         "user_code"   => "required",
         "target"   => "required",
      ]);
      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 401, "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }
      OrdersTarget::updateOrCreate(
         [
            'user_code' => $request->user_code,
            'Deadline' => $request->date ?? $this->lastDayofMonth,

         ],
         [
            'OrdersTarget' => $request->target,
            'bussiness_code' => $request->user()->business_code,
         ]
      );
      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Target assigned for the following users",
         "data" => User::find($request->user_code),
      ]);
   }
   public function assignSaleTarget(Request $request)
   {
      $validator           =  Validator::make($request->all(), [
         "user_code"   => "required",
         "target"   => "required",
      ]);
      if ($validator->fails()) {
         return response()->json(
            [
               "status" => 401, "message" => "validation_error",
               "errors" => $validator->errors()
            ],
            403
         );
      }
      SalesTarget::updateOrCreate(
         [
            'user_code' =>  $request->user_code,
            'Deadline' => $request->date ?? $this->lastDayofMonth,
         ],
         [
            'SalesTarget' => $request->target,
            'bussiness_code' => $request->user()->business_code,
         ]
      );
      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Target assigned for the following users",
         "data" => User::find($request->user_code),
      ]);
   }
}
