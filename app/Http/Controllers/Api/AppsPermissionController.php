<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppPermission;
use Illuminate\Http\Request;

class AppsPermissionController extends Controller
{
    public function getAllPermission(Request $request)
    {
      $permissions=AppPermission::where('user_code',$request->user()->user_code)->get();
      return response()->json([
         "success" => true,
         "message" => "All Permissions",
         "permissions" => $permissions
      ]);
    }
}
