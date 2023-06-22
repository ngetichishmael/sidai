<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Str;

class AuthenticationController extends Controller
{
   public function login(Request $request)
   {
      $random = Str::random(20);
      //(!Auth::attempt(['email' => $request->email, 'password' => $request->password], true))
//      if (!Auth::attempt(
//         [
//            'phone_number' => $request->phone_number,
//            'password' => $request->password,
//            'account_type' =>['RSM','NSM']
//         ],
//         true
//      )) {
//         return response()
//            ->json(['message' => 'Unauthorized'], 401);
//      }
//
//      $user = User::where('email', $request['email'])->firstOrFail();

      $status = false;
      if (is_numeric($request->phone_number)) {
         $status = FacadesAuth::attempt(['phone_number' => $request->phone_number, 'password' => $request->password], true);
      } else {
         $status = FacadesAuth::attempt(['email' => $request->phone_number, 'password' => $request->password], true);
      }
      if ($status == false) {
         return response()->json(['message' => 'Unauthorized'], 401);
      }

      $user = User::where(function ($query) use ($request) {
         $query->where('email', $request->phone_number)
            ->orWhere('phone_number', $request->phone_number);
      })
         ->whereIn('account_type', ['RSM','NSM'])
         ->first();
      if ($user == null) {
         return response()->json(
            [
               'message' => 'Unauthorized',
            ],
            401
         );
      }

      $token = $user->createToken('auth_token')->plainTextToken;

      $activityLog = new activity_log();
      $activityLog->activity = 'Login';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Mobile Login';
      $activityLog->action = 'Logged in successful';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address ="";
      $activityLog->save();

      return response()->json([
         "success" => true,
         "token_type" => 'Bearer',
         "message" => "User Logged in",
         "access_token" => $token,
         "user" => $user
      ]);
   }
}
