<?php

namespace App\Http\Controllers\api\manager;

use App\Http\Controllers\Controller;
use App\Models\customers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
   public function getCustomers()
   {

//      return response()->json([
//         "success" => true,
//         "status" => 200,
//         "data" => customers::where('region_id', Auth::user()->region_id)->get(),
//      ]);

      $customers = customers::with(['orders.orderItems', 'number_visited'])
         ->select('id', 'customer_name', 'id_number', 'customer_type', 'latitude', 'longitude', 'contact_person', 'telephone', 'is_creditor', 'creditor_approved', 'customer_group', 'customer_secondary_group', 'price_group', 'route', 'route_code', 'region_id', 'subregion_id', 'status', 'email', 'image', 'phone_number', 'business_code', 'created_by', 'updated_by', 'created_at', 'updated_at')
         ->where('region_id', Auth::user()->region_id)
         ->get();

      return response()->json([
         "success" => true,
         "status" => 200,
         "data" => $customers,
      ]);

   }
}
