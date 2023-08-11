<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;


class TargetExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.targets', [
            'targets' => $this->data()
         ]);

    }
    public function data()
   {
      $result = DB::table('users AS u')
         ->select(
            'u.name AS user_name',
            'u.account_type AS user_type',
            'lt.LeadsTarget AS leads_target',
            'lt.AchievedLeadsTarget AS leads_achieved',
            'ot.OrdersTarget AS orders_target',
            'ot.AchievedOrdersTarget AS orders_achieved',
            'st.SalesTarget AS sales_target',
            'st.AchievedSalesTarget AS sales_achieved',
            'vt.VisitsTarget AS visits_target',
            'vt.AchievedVisitsTarget AS visits_achieved'
         )
         ->leftJoin('leads_targets AS lt', 'u.user_code', '=', 'lt.user_code')
         ->leftJoin('orders_targets AS ot', 'u.user_code', '=', 'ot.user_code')
         ->leftJoin('sales_targets AS st', 'u.user_code', '=', 'st.user_code')
         ->leftJoin('visits_targets AS vt', 'u.user_code', '=', 'vt.user_code')
         ->get();
      return $result;
   }
}
