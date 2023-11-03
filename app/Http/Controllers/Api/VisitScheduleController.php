<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\visitschedule as ModelsVisitschedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitScheduleController extends Controller
{


    public function index(Request $request)
    {
        $user = $request->user()->user_code;

        // $users = DB::table('users')
        //     ->join('contacts', 'users.id', '=', 'contacts.user_id')
        //     ->join('orders', 'users.id', '=', 'orders.user_id')
        //     ->select('users.*', 'contacts.phone', 'orders.price')
        //     ->get();

        // SELECT `visitschedule`.`Date`, `customers`.`customer_name`
        // FROM `visitschedule` INNER JOIN `customers` ON `customers`.`account` = `visitschedule`.`shopID`;

        $data = DB::table('visitschedule')
        ->leftJoin('customers', 'customers.account', '=', 'visitschedule.shopID')
        ->select(
                'visitschedule.user_code',
                'customers.customer_name',
                'visitschedule.shopID',
                'visitschedule.Date',
                'visitschedule.Type',
                'visitschedule.VisitedStatus')
        ->where('visitschedule.user_code',$user)
        ->get();
        


        return response()->json([
            "success" => true,
            "message" => "All Shop Visits",
            "ShopVisits" => $data,

        ]);
    }

    public function NewVisit(Request $request,$shopID)
    {
        $user = $request->user()->user_code;
        $date = request()->date;

        $schedule = ModelsVisitschedule::create([
            'user_code' => $user,
            'shopID' => $shopID,
            'Type' => 'Individual',
            'Date' => $date,
        ]);
        $schedule->save();

        return response()->json([
            "success" => true,
            "status" => 200,
            "Date"=>$date,
            "message" => "Schedule Added Successfully",
         ]);
    }
}
