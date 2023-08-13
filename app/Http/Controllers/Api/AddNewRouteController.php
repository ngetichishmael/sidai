<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Models\Route_customer;
use App\Models\Route_sales;
use App\Models\Routes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AddNewRouteController extends Controller
{
   /**
    * @param Request $request
    * @return JsonResponse
    */
   public function store(Request $request)
   {

            //$requestID=$request->collect();
           $customerID=$request->get('customer_id');
            $code =  Helper::generateRandomString(20);
            $route = new Routes;
            $route->business_code = Auth::user()->business_code;
            $route->route_code = $code;
            $route->name = $request->name;
            $route->status = $request->status;
            $route->Type = "Individual";
            $route->start_date = $request->start_date;
            $route->end_date = $request->end_date;
            $route->created_by = Auth::user()->user_code;
            $route->save();


      //save customers

            $customers = new Route_customer;
            $customers->business_code  = Auth::user()->business_code;
            $customers->routeID = $code;
            $customers->customerID =$customerID;
            $customers->created_by = Auth::user()->user_code;
            $customers->save();


      //save sales person

            $sales = new Route_sales;
            $sales->business_code  = Auth::user()->business_code;
            $sales->routeID = $code;
            $sales->userID = Auth::user()->id;
            $sales->created_by = Auth::user()->user_code;
            $sales->save();

      return response()->json([
         "success" => true,
         "status" => 200,
         "message" => "Successfully",
      ]);
   }
   public function index(Request $request)
    {
        $user = $request->user()->business_code;

        // $users = DB::table('users')
        //     ->join('contacts', 'users.id', '=', 'contacts.user_id')
        //     ->join('orders', 'users.id', '=', 'orders.user_id')
        //     ->select('users.*', 'contacts.phone', 'orders.price')
        //     ->get();

        // SELECT `visitschedule`.`Date`, `customers`.`customer_name`
        // FROM `visitschedule` INNER JOIN `customers` ON `customers`.`account` = `visitschedule`.`shopID`;

        $data = DB::table('routes')
        ->select(
            'business_code',
            'route_code',
            'name',
            'status',
            'Type',
            'start_date',
            'end_date')
        ->where('routes.business_code',$user)
        ->get();
        // $result = ModelsVisitschedule::join
        // ('customers','customers.account','=','ModelsVisitschedule.shopID')
        // ->select('ModelsVisitschedule.user_code, customers.customer_name')
        // ->where('user_code',$user)
        // ->get();


        return response()->json([
            "success" => true,
            "message" => "All Shop Visits",
            "ShopVisits" => $data,

        ]);
    }
}
