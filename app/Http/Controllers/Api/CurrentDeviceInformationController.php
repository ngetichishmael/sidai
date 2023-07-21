<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CurrentDeviceInformation;
use App\Models\User;
use Illuminate\Http\Request;

class CurrentDeviceInformationController extends Controller
{
    public function postCurrentDeviceInformation(Request $request)
    {

        CurrentDeviceInformation::create(
            [
                "user_code" => $request->user()->user_code,
                "current_gps" => $request->current_gps,
                "current_battery_percentage" => $request->current_battery_percentage,
                "device_code" => $request->device_code,
                "IMEI" => $request->IMEI,
                "fcm_token" => $request->fcm_token,
                "android_version" => $request->android_version,
            ]
        );

        return response()->json([
            "success" => true,
            "message" => "Data posted successfully",
        ]);
    }
    public function getUserCoordinates(Request $request, $userCode)
    {
        // Fetch the marker data for the specified userCode
        $markerData = CurrentDeviceInformation::where('user_code', $userCode)->orderBy('id', 'DESC')->limit(25)->get();

        // Prepare the data for the map
        $markers = [];
        foreach ($markerData as $data) {
            $coordinates = explode(',', $data->current_gps);
            $lat = (float) $coordinates[0];
            $lng = (float) $coordinates[1];

            // Add the marker data to the $markers array
            $markers[] = [
                'lat' => $lat,
                'lng' => $lng,
                'title' => User::where('user_code', $data->user_code)->pluck('name')->implode(''),
                'battery' => $data->current_battery_percentage,
                'android_version' => $data->android_version,
                'IMEI' => $data->IMEI,
                'description' => $data->updated_at->diffForHumans(),
                // Add other marker properties as needed
            ];
        }

        // Return the marker data as JSON response
        return response()->json($markers);
    }
}
