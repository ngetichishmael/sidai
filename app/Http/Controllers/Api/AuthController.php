<?php

namespace App\Http\Controllers\Api;

use App\Helpers\SMS;
use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\User;
use App\Models\UserCode;
use Carbon\Carbon;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB as FacadesDB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @group Authentication Api's
 *
 * APIs to manage the user Authentication.
 * */
class AuthController extends Controller
{

    /**
     * User Login
     *
     * @bodyParam email string required
     * @bodyParam password string required user password.
     * @response 200{
    "message": "Hi User Name, welcome",
    "access_token": "254|33icFsR8uIOF1KsOeaJ114ntrU8adIX7gitwAveK",
    "token_type": "Bearer"
    }
     **/
    public function userLogin(Request $request)
    {
        $status = false;
        if (is_numeric($request->email)) {
            $status = FacadesAuth::attempt(['phone_number' => $request->email, 'password' => $request->password], true);
        } else {
            $status = FacadesAuth::attempt(['email' => $request->email, 'password' => $request->password], true);
        }
        if ($status == false) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::with('region')->where(function ($query) use ($request) {
            $query->where('email', $request->email)
                ->orWhere('phone_number', $request->email);
        })
            ->whereIn('account_type', ['TD', 'TSR', 'RSM', 'NSM'])
            ->first();
        if ($user == null) {
            return response()->json(
                [
                    'message' => 'Unauthorized',
                ],
                401
            );
        }
        // $user = User::where('phone_number', $request['email'])->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        $random = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Login in Mobile Device';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Mobile';
        $activityLog->action = 'User ' . auth()->user()->name . ' Logged in in mobile appication';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = "";
        $activityLog->save();

        return response()->json([
            "success" => true,
            "token_type" => 'Bearer',
            "message" => "User Logged in",
            "access_token" => $token,
            "user" => $user,
        ]);
    }
    public function userLoginPhone(Request $request)
   {
      $user = User::where('phone_number', $request['phone_number'])->first();
      if(!$user){
      return response()
            ->json(['message' => 'Unauthorized'], 401);}

      // Call sendOTP function after user existence check
     $this->sendOTP($request['phone_number']);

      $user = User::where('phone_number', $request['phone_number'])->firstOrFail();
      

      $token = $user->createToken('auth_token')->plainTextToken;

      return response()->json([
         "success" => true,
         "token_type" => 'Bearer',
         "message" => "User Logged in",
         "access_token" => $token,
         "user" => $user
      ]);
   }

    /**
     * User details
     * @param  string $email
     * @response 200{
    "success": true,
    "message": "Restaurants menu items",
    "data": {
    "id": .....,
    "name": "Albert Einstein",
    "email": "einstein@email.com",
    }
    }
     **/
    public function user_details($email)
    {
        $user = User::where('email', $email)->first();
        return response()->json([
            "success" => true,
            "message" => "User Details",
            "data" => $user,
        ]);
    }

    /**
     * Logout and delete token
     *
     *
     **/
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $random = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Logout in Mobile Device';
        $activityLog->user_code = $request->user()->user_code;
        $activityLog->section = 'Mobile';
        $activityLog->action = 'User ' . $request->user()->name . ' Logged out of mobile appication';
        $activityLog->userID = $request->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = "";
        $activityLog->save();

        return [
            'message' => 'You have successfully logged out',
        ];
    }

    /**
     * send otp
     *
     * @return \Response
     */

     public function sendOTP($number)
   {

      $user = FacadesDB::table('users')->where('phone_number', $number)->first();

      if ($user) {
         $code = rand(100000, 999999);

         UserCode::updateOrCreate([
            'user_id' => $user->id,
            'code' => $code
         ]);

         $response = $this->sendUserSMS($code, $number);

         return response()->json(
            [
               "response" => $response,
               'data' => $user,
               'otp' => $code
            ],
            200
         );
      } else {
         return response()->json(
            [
               'message' => 'User is not registered!'
            ],
            406
         );
      }
   }
   
   public function sendUserSMS($code, $phone_number)
   {
      $curl = curl_init();
      $url = 'https://accounts.jambopay.com/auth/token';
      curl_setopt($curl, CURLOPT_URL, $url);

      curl_setopt(
         $curl,
         CURLOPT_HTTPHEADER,
         array(
            'Content-Type: application/x-www-form-urlencoded',
         )
      );

      curl_setopt(
         $curl,
         CURLOPT_POSTFIELDS,
         http_build_query(
            array(
               'grant_type' => 'client_credentials',
               'client_id' => "qzuRm3UxXShEGUm2OHyFgHzkN1vTkG3kIVGN2z9TEBQ=",
               'client_secret' => "36f74f2b-0911-47a5-a61b-20bae94dd3f1gK2G2cWfmWFsjuF5oL8+woPUyD2AbJWx24YGjRi0Jm8="
            )
         )
      );

      curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
      $curl_response = curl_exec($curl);

      $token = json_decode($curl_response);
      curl_close($curl);

      $message = 'Your verification code is ' . $code;

      $curl = curl_init();

      curl_setopt_array($curl, array(
         CURLOPT_URL => 'https://swift.jambopay.co.ke/api/public/send',
         CURLOPT_RETURNTRANSFER => true,
         CURLOPT_ENCODING => '',
         CURLOPT_MAXREDIRS => 10,
         CURLOPT_TIMEOUT => 0,
         CURLOPT_FOLLOWLOCATION => true,
         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
         CURLOPT_CUSTOMREQUEST => 'POST',
         CURLOPT_POSTFIELDS => json_encode(
            array(
               "sender_name" => "PASANDA",
               "contact" => $phone_number,
               "message" => $message,
               "callback" => "https://pasanda.com/sms/callback"
            )
         ),

         CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Bearer ' . $token->access_token
         ),
      ));

      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
   }

    // public function sendOTP($number)
    // {

    //     $user = User::where('phone_number', $number)->get();

    //     if ($user != null) {
    //        $check=UserCode::where('user_id' ,'=',  $user[0]->id)->first();
    //        $currentTimestamp = Carbon::now();
    //        $thresholdTimestamp = $currentTimestamp->subSeconds(360);

    //        if ($check->created_at->greaterThan($thresholdTimestamp)) {
    //            return response()->json(['data' => $user, 'otp' => $check->code]);
    //        } else{

    //         try {
    //             $code = rand(100000, 999999);
    //             UserCode::updateOrCreate([
    //                 'user_id' => $user[0]->id,
    //                 'code' => $code,
    //             ]);
    //             $message = "Your Sidai OTP is " . $code;
    //             (new SMS)($number, $message);
    //             return response()->json(['data' => $user, 'otp' => $code]);
    //         } catch (ExceptionHandler $e) {
    //             return response()->json(['message' => 'Error occurred while trying to send OTP code']);
    //         }
    //     }
    //     }
    //     else {
    //         return response()->json(['message' => 'User is not registered!']);
    //     }
    // }

    /**
     * verify otp
     *
     * @return response()
     */

     public function verifyOTP($number, $otp)
   {
      $user = DB::table('users')->where('phone_number', $number)->get();

      // return $user;

      $exists = UserCode::where('user_id', $user[0]->id)
         ->where('code', $otp)
         ->where('updated_at', '>=', now()->subMinutes(5))
         ->latest('updated_at')
         ->exists();

         if ($exists) {
            $user = User::where('phone_number', $number)->firstOrFail();
   
         $token = $user->createToken('auth_token')->plainTextToken;
         return response()->json([
            "success" => true,
            "token_type" => 'Bearer',
            "message" => "User Logged in",
            "access_token" => $token,
            "user" => $user
         ]);
   
            // return response()->json(['message' => 'Valid OTP entered']);
         }
      // Log::error('Invalid OTP entered');
      return response()->json(['message' => 'Invalid OTP entered']);
   }
    // public function verifyOTP($number, $otp)
    // {

    //     $user = DB::table('users')->where('phone_number', $number)->get();
    //     $exists = UserCode::where('user_id', $user[0]->id)
    //         ->where('code', $otp)
    //         ->where('updated_at', '>=', now()->subMinutes(5))
    //         ->latest('updated_at')
    //         ->exists();

    //     if ($exists) {
    //         $random = Str::random(20);
    //         $activityLog = new activity_log();
    //         $activityLog->activity = 'Login';
    //         $activityLog->user_code = auth()->user()->user_code;
    //         $activityLog->section = 'Mobile';
    //         $activityLog->action = 'Logged in successful';
    //         $activityLog->userID = auth()->user()->id;
    //         $activityLog->activityID = $random;
    //         $activityLog->ip_address = "";
    //         $activityLog->save();
    //         return response()->json(['message' => 'Valid OTP entered']);
    //     }
    //     // Log::error('Invalid OTP entered');
    //     return response()->json(['message' => 'Invalid OTP entered']);
    // }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'phone_number' => 'required|string|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',

        ]);

        User::where('phone_number', $request->phone_number)->update(['password' => Hash::make($request->password)]);
        $random = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Password  updating';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Mobile';
        $activityLog->action = 'Password ' . $request->product_name . ' successfully updated ';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = $request->ip();
        $activityLog->save();

        return response()->json(['message' => 'Password has been changed sucessfully']);
        // DB::table('password_resets')->where(['email'=> $request->email])->delete();

    }
}
