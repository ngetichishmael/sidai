<?php

namespace App\Http\Livewire\Reports;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\User;
use App\Models\Orders;
use Livewire\Component;
use App\Models\Subregion;
use Livewire\WithPagination;
use App\Exports\VansaleExport;
use App\Exports\EmployeesExport;
use App\Models\customer\customers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Employee extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   public $user;
   public $orderBy = 'id';
   public $orderAsc = true;
   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $count = 1;
      return view('livewire.reports.employees', [
         'employees' => $this->getEmployees(),
         'count' =>$count
      ]);
   }
   // public function employees()
   // {
   //    $query = Orders::with('User', 'Customer');
   //    $query->whereIn('customerID', $this->filter())->where('order_type', 'Van sales');
   //    if (!is_null($this->start)) {
   //       if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
   //          $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
   //       } else {
   //          if (is_null($this->end)) {
   //             $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
   //          }
   //          $query->whereBetween('created_at', [$this->start, $this->end]);
   //       }
   //    }

   //    return $query->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
   //       ->paginate(25);
   // }
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
               DB::raw('sales_targets.SalesTarget - sales_targets.AchievedSalesTarget as sales_difference'),
               DB::raw('leads_targets.LeadsTarget - leads_targets.AchievedLeadsTarget as leads_difference')
            )->get();
    }
   
   public function export()
    {
        $filteredCustomers = $this->customers();
        return Excel::download(new EmployeesExport($filteredCustomers), 'employees.xlsx');
    }
    public function exportCSV()
    {
        $filteredCustomers = $this->customers();
        return Excel::download(new EmployeesExport($filteredCustomers), 'employees.csv');
    }

    public function exportPDF()
    {
        $data = [
            'contacts' => $this->customers(),
        ];
        $pdf = PDF::loadView('Exports.customer_pdf', $data);

        // Add the following response headers
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'employees.pdf');
    }
}
