<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper as HelpersHelper;
use App\Http\Controllers\Controller;
use App\Models\Routes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddRouteController extends Controller
{
   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {


      $code =  HelpersHelper::generateRandomString(20);
      $route = new Routes;
      $route->business_code = Auth::user()->business_code;
      $route->route_code = $code;
      $route->name = $request->name;
      $route->status = $request->status;
      $route->start_date = $request->start_date;
      $route->end_date = $request->end_date;
      $route->created_by = Auth::user()->user_code;
      $route->save();


    //   //save customers
    //   $customersCount = count(collect($request->customers));
    //   if ($customersCount > 0) {
    //      for ($i = 0; $i < count($request->customers); $i++) {
    //         $customers = new Route_customer;
    //         $customers->business_code  = Auth::user()->business_code;
    //         $customers->routeID = $code;
    //         $customers->customerID = $request->customers[$i];
    //         $customers->created_by = Auth::user()->user_code;
    //         $customers->save();
    //      }
    //   }

    //   //save sales person
    //   $salescount = count(collect($request->sales_persons));
    //   if ($salescount > 0) {
    //      for ($i = 0; $i < count($request->sales_persons); $i++) {
    //         $sales = new Route_sales;
    //         $sales->business_code  = Auth::user()->business_code;
    //         $sales->routeID = $code;
    //         $sales->userID = $request->sales_persons[$i];
    //         $sales->created_by = Auth::user()->user_code;
    //         $sales->save();
    //      }
    //   }
      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Successfully",
      ]);
   }
}
