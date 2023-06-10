<?php

namespace App\Http\Controllers\app;

use Helper;
use Session;
use App\Models\User;
use App\Models\zone;
use App\Models\Routes;
use App\Models\Area;
use App\Models\UnitRoute;
use App\Models\Route_sales;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Route_customer;
use App\Models\customer\customers;
use App\Http\Controllers\Controller;
use App\Models\Relationship;
use Illuminate\Support\Facades\Auth;

class routesController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function index()
   {
      return view('app.routes.index');
   }

   /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
   public function create()
   {
      $customers = customers::where('business_code', Auth::user()->business_code)->pluck('customer_name', 'id');
      $salesPeople = User::where('business_code', Auth::user()->business_code)->where('account_type','Sales')->pluck('name', 'id');
      

      return view('app.routes.create', ['customers'=>$customers, 'salesPeople'=>$salesPeople]);
   }

   /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
   public function store(Request $request)
   {
      $this->validate($request, [
         'name' => 'required',
         'status' => 'required',
         'end_date' => 'required',
      ]);

      $code =  Str::random(20);
      $route = new Routes;
      $route->business_code = Auth::user()->business_code;
      $route->route_code = $code;
      $route->name = $request->name;
      $route->status = $request->status;
      $route->Type = "Assigned";
      $route->start_date = $request->start_date;
      $route->end_date = $request->end_date;
      $route->created_by = Auth::user()->user_code;
      $route->save();


      //save customers
      $customersCount = count(collect($request->customers));
      if ($customersCount > 0) {
         for ($i = 0; $i < count($request->customers); $i++) {
            $customers = new Route_customer;
            $customers->business_code  = Auth::user()->business_code;
            $customers->routeID = $code;
            $customers->customerID = $request->customers[$i];
            $customers->created_by = Auth::user()->user_code;
            $customers->save();
         }
      }

      //save sales person
      $salescount = count(collect($request->sales_persons));
      if ($salescount > 0) {
         for ($i = 0; $i < count($request->sales_persons); $i++) {
            $sales = new Route_sales;
            $sales->business_code  = Auth::user()->business_code;
            $sales->routeID = $code;
            $sales->userID = $request->sales_persons[$i];
            $sales->created_by = Auth::user()->user_code;
            $sales->save();
         }
      }

      Session()->flash('success', 'Route successfully added');


      return redirect()->route('routes.index');
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