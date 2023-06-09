<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
   public function getReports(Request $request)
   {
      $user_code = $request->user()->user_code;
      // $countWeek = ModelsCustomers::whereBetween('updated_at', [
      //    now()->startOfWeek(), now()->endOfWeek()
      // ])->count();

      // $countMonth = ModelsCustomers::whereMonth('created_at', now())
      //    ->count();

      // $countYear = ModelsCustomers::whereYear('updated_at', now())
      //    ->count();
      $countOrdersToday = Orders::where('user_code', $user_code)
         ->whereDate('created_at', '=', Carbon::today()->toDateString())
         ->where('order_status', 'DELIVERED')
         ->where('order_type', 'Van sales')
         ->get();
      $countOrdersWeekly = Orders::where('user_code', $user_code)
         ->whereBetween('created_at', [
            now()->startOfWeek(), now()->endOfWeek()
         ])
         ->where('order_status', 'DELIVERED')
         ->where('order_type', 'Van sales')
         ->get();
      $countOrdersMonthly = Orders::where('user_code', $user_code)
         ->whereMonth('created_at', today())
         ->where('order_type', 'Van sales')
         ->where('order_status', 'DELIVERED')
         ->get();
      $totalSalesToday = Orders::where('user_code', $user_code)
         ->whereDate('created_at', '=', Carbon::today()->toDateString())
         ->where('order_type', 'Van sales')
         ->where('order_status', 'DELIVERED')
         ->sum('price_total');
      $totalSalesWeekly = Orders::where('user_code', $user_code)
         ->whereBetween('created_at', [
            now()->startOfWeek(), now()->endOfWeek()
         ])
         ->where('order_type', 'Van sales')
         ->where('order_status', 'DELIVERED')
         ->sum('price_total');
      $totalSalesMonthly = Orders::where('user_code', $user_code)
         ->whereMonth('created_at', today())
         ->where('order_type', 'Van sales')
         ->where('order_status', 'DELIVERED')
         ->sum('price_total');

      return response()->json([
         "success" => true,
         "status" => 200,
         "Data" => [
            'OrdersToday' => $countOrdersToday,
            'OrdersWeekly' => $countOrdersWeekly,
            'OrdersMonthly' => $countOrdersMonthly,
            'totalSalesToday' => $totalSalesToday,
            'totalSalesWeekly' => $totalSalesWeekly,
            'totalSalesMonthly' => $totalSalesMonthly,
         ]
      ]);
   }
}
