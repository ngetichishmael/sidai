<?php

namespace App\Http\Livewire\Visits\Users;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $visits = User::join('customer_checkin', 'users.user_code', '=', 'customer_checkin.user_code')
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
      return view('livewire.visits.users.dashboard', [
         'visits' => $visits
      ]);
   }
}
