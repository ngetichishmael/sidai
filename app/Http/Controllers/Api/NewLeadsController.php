<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\customers as ModelsCustomers;

class NewLeadsController extends Controller
{

    public function index(){
        $countWeek= ModelsCustomers::whereBetween('created_at',[
            now()->startOfWeek(),now()->endOfWeek()
        ])->count();
        $countMonth=ModelsCustomers::whereMonth('created_at',now())
        ->count();
        $countYear=ModelsCustomers::whereYear('created_at',now())
        ->count();
        // $result = [
        //     "success" => true,
        //     "message" => "Orders Per Week, Month and Year respectively ",
        //     "ThisWeekLeads"=>$countWeek,
        //     "ThisMonthLeads"=>$countMonth,
        //     "ThisYearLeads"=>$countYear,
        // ];

        return response()->json([
            "success" => true,
            "message" => "Orders Per Week, Month and Year respectively ",
            "ThisWeekLeads"=>$countWeek,
            "ThisMonthLeads"=>$countMonth,
            "ThisYearLeads"=>$countYear,
         ]);
    }
}
