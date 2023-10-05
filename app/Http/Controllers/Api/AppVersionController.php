<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AppVersion;
use Illuminate\Http\Request;

class AppVersionController extends Controller
{
   public function index()
   {
      // Retrieve a list of app versions
      $appVersions = AppVersion::all();
      return response()->json($appVersions);
   }

   public function store(Request $request)
   {
      // Create a new app version
      $appVersion = AppVersion::create($request->all());
      return response()->json($appVersion, 201);
   }

   public function show($id)
   {
      // Retrieve a specific app version by ID
      $appVersion = AppVersion::findOrFail($id);
      return response()->json($appVersion);
   }

   public function update(Request $request, $id)
   {
      // Update a specific app version by ID
      $appVersion = AppVersion::findOrFail($id);
      $appVersion->update($request->all());
      return response()->json($appVersion);
   }

   public function destroy($id)
   {
      // Delete a specific app version by ID
      $appVersion = AppVersion::findOrFail($id);
      $appVersion->delete();
      return response()->json(null, 204);
   }
}
