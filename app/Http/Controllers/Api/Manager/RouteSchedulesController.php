<?php

namespace App\Http\Controllers\Api\Manager;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class RouteSchedulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {       $show_routes = FacadesDB::table('route_sales')
          ->join('route_customer', 'route_customer.routeID', '=', 'route_sales.routeID')
          ->join('routes', 'routes.route_code', '=', 'route_sales.routeID')
          ->join('customers', 'customers.id', '=', 'route_customer.customerID')
          ->where('routes.end_date', '>=', Carbon::now())
          ->select('routes.name','route_sales.userID','routes.route_code','routes.status','routes.Type', FacadesDB::raw('CURDATE() as start_date'),'routes.end_date',
             'customers.id as customer_id','customers.account','customers.customer_name','customers.address',
             'customers.email','customers.phone_number','customers.latitude','customers.longitude')
          ->get();
       return response()->json([
          "success" => true,
          "message" => "All Assigned Routes Schedules fetched successfully",
          "user routes" => $show_routes,
       ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
