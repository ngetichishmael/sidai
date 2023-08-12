<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders as ModelsOrders;
use Illuminate\Http\Request;

class OrdersCountController extends Controller
{
    public function index(Request $request){

        $user=$request->user()->id;



        $countWeek= ModelsOrders::whereBetween('delivery_date',[
            now()->startOfWeek(),now()->endOfWeek()
        ])
        ->where('customerID',$user)
        ->count();

        // SELECT COUNT(*) AS YEAR FROM `orders` WHERE YEAR(`orders`.`delivery_date`)=YEAR(NOW())
        // $users = DB::table('users')
        // ->whereYear('created_at', '2016')
        // ->get();
        $countMonth=ModelsOrders::whereMonth('delivery_date',now())
        ->where('customerID',$user)
        ->count();
        $countYear=ModelsOrders::whereYear('delivery_date',now())
        ->where('customerID',$user)
        ->count();

        // $result = [
        //     "success" => true,
        //     "message" => "Orders Per Week, Month and Year respectively ",
        //     "User Id"=>$user,
        //     "CustomerOrderCountThisWeek"=>$countWeek,
        //     "CustomerOrderCountThisMonth"=>$countMonth,
        //     "CustomersOrderCountThisYear"=>$countYear,
        // ];

        return response()->json([
            "success" => true,
            "message" => "Orders Per Week, Month and Year respectively ",
            "User Id"=>$user,
            "CustomerOrderCountThisWeek"=>$countWeek,
            "CustomerOrderCountThisMonth"=>$countMonth,
            "CustomersOrderCountThisYear"=>$countYear,
        ]);
    }
}
