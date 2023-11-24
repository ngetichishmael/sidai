<?php

namespace App\Exports;

use App\Models\customers;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromView;

class EmployeesExport implements FromView
{

   public function view(): View
   {

      return view('Exports.employees', [
         'employees' => $this->getEmployees(),
      ]);
   }

   public function getEmployees()
        {
            return User::join('customer_checkin', function ($join) {
                    $join->on('users.user_code', '=', 'customer_checkin.user_code')
                        ->whereRaw('customer_checkin.start_time <= customer_checkin.stop_time');
                })
                ->leftJoin('leads_targets', 'users.user_code', '=', 'leads_targets.user_code')
                ->leftJoin('sales_targets', 'users.user_code', '=', 'sales_targets.user_code')
                ->select(
                    'users.name as name',
                    'users.account_type as role',
                    DB::raw('COUNT(DISTINCT customer_checkin.id) as visit_count'),
                    DB::raw('sales_targets.SalesTarget as sales'),
                    DB::raw('sales_targets.AchievedSalesTarget as achieved_sales'),
                    DB::raw('leads_targets.LeadsTarget as leads'),
                    DB::raw('leads_targets.AchievedLeadsTarget as achieved_leads')
                )
                ->get();
        }
}

