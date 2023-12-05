<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Helper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;
use Session;

class routeScheduleController extends Controller
{
   /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function show($id)
   {

     $show_routes = FacadesDB::table('route_sales')
     ->where('route_sales.userID', (string)$id)
     ->join('route_customer', 'route_customer.routeID', '=', 'route_sales.routeID')
     ->join('routes', 'routes.route_code', '=', 'route_sales.routeID')
     ->join('customers', 'customers.id', '=', 'route_customer.customerID')
      ->where('routes.end_date', '=<', Carbon::now())
     ->select('routes.name','route_sales.userID','routes.route_code','routes.status','routes.Type', FacadesDB::raw('CURDATE() as start_date'),'routes.end_date',
     'customers.id as customer_id','customers.account','customers.customer_name','customers.address',
     'customers.email','customers.phone_number','customers.latitude','customers.longitude')
     ->get();
     return response()->json([
         "success" => true,
         "message" => "Assigned Routes fetched successfully",
         "user routes" => $show_routes,
      ]);
   }

   /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function edit($id)
   {
      //
   }

   /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function update(Request $request, $id)
   {
      //
   }

   /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
   public function destroy($id)
   {
      //
   }
}
