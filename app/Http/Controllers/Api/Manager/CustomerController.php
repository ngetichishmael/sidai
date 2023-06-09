<?php

namespace App\Http\Controllers\api\manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
   public function getCustomers()
   {
      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => User::where('account_type', 'Customer')->where('region_id', Auth::user()->region_id)->get(),
      ]);
   }
}
