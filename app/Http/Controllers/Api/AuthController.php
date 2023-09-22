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

        $user = User::where(function ($query) use ($request) {
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
        $activityLog->section = 'Mobile Login';
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
        $activityLog->section = 'Mobile Logout';
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

        $user = User::where('phone_number', $number)->get();

        if ($user != null) {
           $check=UserCode::where('user_id' ,'=',  $user[0]->id)->first();
           $currentTimestamp = Carbon::now();
           $thresholdTimestamp = $currentTimestamp->subSeconds(360);

           if ($check->created_at->greaterThan($thresholdTimestamp)) {
               return response()->json(['data' => $user, 'otp' => $check->code]);
           } else{

            try {
                $code = rand(100000, 999999);
                UserCode::updateOrCreate([
                    'user_id' => $user[0]->id,
                    'code' => $code,
                ]);
                $message = "Your Sidai OTP is " . $code;
                (new SMS)($number, $message);
                return response()->json(['data' => $user, 'otp' => $code]);
            } catch (ExceptionHandler $e) {
                return response()->json(['message' => 'Error occurred while trying to send OTP code']);
            }
        }
        }
        else {
            return response()->json(['message' => 'User is not registered!']);
        }
    }

    /**
     * verify otp
     *
     * @return response()
     */
    public function verifyOTP($number, $otp)
    {

        $user = DB::table('users')->where('phone_number', $number)->get();
        $exists = UserCode::where('user_id', $user[0]->id)
            ->where('code', $otp)
            ->where('updated_at', '>=', now()->subMinutes(5))
            ->latest('updated_at')
            ->exists();

        if ($exists) {
            $random = Str::random(20);
            $activityLog = new activity_log();
            $activityLog->activity = 'Login';
            $activityLog->user_code = auth()->user()->user_code;
            $activityLog->section = 'Mobile Login';
            $activityLog->action = 'Logged in successful';
            $activityLog->userID = auth()->user()->id;
            $activityLog->activityID = $random;
            $activityLog->ip_address = "";
            $activityLog->save();
            return response()->json(['message' => 'Valid OTP entered']);
        }
        // Log::error('Invalid OTP entered');
        return response()->json(['message' => 'Invalid OTP entered']);
    }

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
        $activityLog->section = 'Password update';
        $activityLog->action = 'Password ' . $request->product_name . ' successfully updated ';
        $activityLog->userID = auth()->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = $request->ip();
        $activityLog->save();

        return response()->json(['message' => 'Password has been changed sucessfully']);
        // DB::table('password_resets')->where(['email'=> $request->email])->delete();

    }
}
