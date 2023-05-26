<?php

namespace App\Http\Livewire\Visits\Users;

use App\Exports\UsersVisitsExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
   use WithPagination;
   public $start;
   public $end;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public function render()
   {
      return view('livewire.visits.users.dashboard', [
         'visits' => $this->data()
      ]);
   }
   public function data()
   {
      $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
      $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
      $this->start = $this->start == null ? $start_date : $this->start;
      $this->end = $this->end == null ? $end_date : $this->end;
      $searchTerm = '%' . $this->search . '%';
      $visits = User::join('customer_checkin', 'users.user_code', '=', 'customer_checkin.user_code')
         ->whereBetween('customer_checkin.updated_at', [$this->start, $this->end])
         ->select(
            'users.name as name',
            'users.user_code as user_code',
            DB::raw('COUNT(customer_checkin.id) as visit_count'),
            DB::raw('SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time)))) as average_time'),
            DB::raw('SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time)))) as total_time_spent'),
            DB::raw('TIMEDIFF(MAX(customer_checkin.stop_time), MIN(customer_checkin.start_time)) as total_trading_time')
         )
         ->where('users.name', 'like', $searchTerm)
         ->groupBy('users.name')
         ->paginate($this->perPage);
      return $visits;
   }
   public function updatedStart()
   {
      //  $this->mount();
      $this->render();
   }
   public function updatedEnd()
   {
      //  $this->mount();
      $this->render();
   }
   public function export()
   {
      return Excel::download(new UsersVisitsExport($this->data()), 'visits.xlsx');
   }
}
