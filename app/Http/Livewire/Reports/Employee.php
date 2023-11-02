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
   public function employees()
   {
      $query = Orders::with('User', 'Customer');
//      if (!$this->user->account_type === 'RSM') {
//         $query->whereIn('customerID', $this->filter());
//      }
      $query->whereIn('customerID', $this->filter())->where('order_type', 'Van sales');
      if (!is_null($this->start)) {
         if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
            $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
         } else {
            if (is_null($this->end)) {
               $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
         }
      }

      return $query->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
         ->paginate(25);
   }
   public function getEmployees()
    {
        return User::join('customer_checkin', function ($join) {
         $join->on('users.user_code', '=', 'customer_checkin.user_code')
             ->whereRaw('customer_checkin.start_time <= customer_checkin.stop_time');
     })
            ->select(
               'users.name as name',
               'users.account_type as role',
               DB::raw('COUNT(customer_checkin.id) as visit_count'),
            )->get();
    }
   public function filter(): array
   {

      $array = [];
//      $user = $this->user;
      $user_code = $this->user->user_code;
      $dataAccessLevel = $this->user->roles()->pluck('data_access_level')->first();
      $subregions = Subregion::where('region_id', $this->user->region_id)->pluck('id');
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if (auth()->check() && $dataAccessLevel == 'route') {
         $customers = customers::whereIn('route', $areas)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'subregional') {
         $customers = customers::whereIn('subregion_id', $subregions)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'regional') {
         $customers = customers::where('region_id', $this->user->region_id)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'all') {
         $customers = customers::all()->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }
      else{
         return $array;
      }
//      if (!$user->account_type === 'RSM') {
//         return $array;
//      }
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
