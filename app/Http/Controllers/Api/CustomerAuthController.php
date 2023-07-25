<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\activity_log;
use App\Models\customer\customers;
use App\Models\Region;
use App\Models\Routes;
use App\Models\Subregion;
use App\Models\User;
use App\Models\UserCode;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CustomerAuthController extends Controller
{
    public function customerLogin(Request $request)
    {

        if (!Auth::attempt(
            [
                'phone_number' => $request->phone_number,
                'password' => "password",
                'account_type' => 'Customer',
            ],
            true
        )) {
            return response()
                ->json(['message' => 'Unauthorized'], 401);
        }

        $user = User::where('phone_number', $request['phone_number'])->firstOrFail();
        User::where('phone_number', $request['phone_number'])
            ->where('account_type', "Customer")
            ->update([
                "fcm_token" => $request->fcm_token,
            ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        $random = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Login in Mobile Device';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Mobile Login';
        $activityLog->action = 'Customer ' . auth()->user()->name . ' Logged in mobile appication';
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
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        $random = Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Logout of Mobile Device';
        $activityLog->user_code = $request->user()->user_code;
        $activityLog->section = 'Mobile Logout';
        $activityLog->action = 'Customer' . $request->user()->name . ' Logged out in mobile appication';
        $activityLog->userID = $request->user()->id;
        $activityLog->activityID = $random;
        $activityLog->ip_address = "";
        $activityLog->save();

        return [
            'message' => 'You have successfully logged out',
        ];
    }
    public function registerCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "customer_name" => "required|unique:customers",
            "phone_number" => "required|unique:customers",
            "Latitude" => "required",
            "Longitude" => "required",
            "image" => "required|image",
        ]);
        info($request);
        if ($validator->fails()) {
            return response()->json(
                [
                    "status" => 403,
                    "message" => "validation_error",
                    "errors" => $validator->errors(),
                ],
                403
            );
        }
        $rand = Str::random(3);
        $image_path = $request->file('image')->store('image', 'public');
        $account = Str::random(20);
        $route = Routes::where('id', $request->route_code)->first();
        $subregion = Subregion::where('id', $route->subregion_id)->first();
        $region = Region::where('id', $subregion->region_id)->first();

        User::create([
            'user_code' => $account,
            'name' => $request->customer_name,
            'email' => $request->email ?? $request->customer_name . $rand . '@gmail.com',
            'password' => Hash::make($request->phone_number),
            'business_code' => $account,
            'phone_number' => $request->phone_number,
            'location' => $request->Address,
            'account_type' => "Customer",
            'status' => "Active",
            'region_id' => $region->id,
        ]);

        customers::create([
            'customer_name' => $request->customer_name,
            'account' => $account,
            'user_code' => $account,
            'approval' => "Approved",
            'address' => $request->Address,
            'country' => "Kenya",
            'latitude' => $request->Latitude,
            'longitude' => $request->Longitude,
            'contact_person' => $request->ContactPerson,
            'phone_number' => $request->phone_number,
            'Telephone' => $request->phone_number,
            'customer_group' => $request->CustomerLevel,
            'route' => $request->route_code,
            'route_code' => $request->route_code,
            'status' => "Active",
            'email' => $request->email,
            'region_id' => $region->id,
            'subregion_id' => $subregion->id,
            'image' => $image_path,
            'business_code' => $account,
            'created_by' => $account,
            'updated_by' => $account,
        ]);

        $user = User::where('phone_number', $request->phone_number)->firstOrFail();

        $token = $user->createToken('auth_token')->plainTextToken;
        $random = \Illuminate\Support\Str::random(20);
        $activityLog = new activity_log();
        $activityLog->activity = 'Register Customer';
        $activityLog->user_code = auth()->user()->user_code;
        $activityLog->section = 'Customer Registration';
        $activityLog->action = 'User ' . auth()->user()->name . ' registered customer' . $request->customer_name;
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
    public function sendOTP($number)
    {

        $user = DB::table('users')->where('phone_number', $number)->get();

        if ($user->isNotEmpty()) {
            try {

                $code = rand(100000, 999999);

                UserCode::updateOrCreate([
                    'user_id' => $user[0]->id,
                    'code' => $code,
                ]);

                $curl = curl_init();

                $url = 'https://accounts.jambopay.com/auth/token';
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                    'Content-Type: application/x-www-form-urlencoded',
                )
                );

                curl_setopt($curl, CURLOPT_POSTFIELDS,
                    http_build_query(array('grant_type' => 'client_credentials', 'client_id' => config('services.jambopay.sms_client_id'), 'client_secret' => config('services.jambopay.sms_client_secret'))));

                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $curl_response = curl_exec($curl);

                $token = json_decode($curl_response);
                curl_close($curl);

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
                            "sender_name" => "SOKOFLOW",
                            "contact" => $number,
                            "message" => $code,
                            "callback" => "https://pasanda.com/sms/callback",
                        )
                    ),
                    CURLOPT_HTTPHEADER => array(
                        'Content-Type: application/json',
                        'Authorization: Bearer ' . $token->access_token,
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);
                return $response;
//            return response()->json(['data' => $user, 'otp' => $code]);
            } catch (ExceptionHandler $e) {
                return response()->json(
                    [
                        'message' => 'Error occured while trying to send OTP code',
                    ],
                    403
                );
            }
        } else {
            return response()->json(
                [
                    'message' => 'User is not registered!',
                ],
                403
            );
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
            return response()->json(
                [
                    'message' =>
                    'Valid OTP entered',
                ],
                200
            );
        }
        return response()->json(
            [
                'message' => 'Invalid OTP entered',
            ],
            403
        );
    }

    public function updatePassword(Request $request)
    {

        $request->validate([
            'phone_number' => 'required|string|exists:users',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required',

        ]);

        $user = User::where('phone_number', $request->phone_number)
            ->update(['password' => Hash::make($request->password)]);

        return response()->json(
            [
                'message' => 'Password has been changed sucessfully',
                "User" => $user,
            ],
            402
        );
        // DB::table('password_resets')->where(['email'=> $request->email])->delete();

    }
}
