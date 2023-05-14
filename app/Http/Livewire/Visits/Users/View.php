<?php

namespace App\Http\Livewire\Visits\Users;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class View extends Component
{
    use WithPagination;
    public $user_code;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public ?string $search = null;
    public function render()
    {
        $visits = DB::table('users')
            ->join('customer_checkin', 'users.user_code', '=', 'customer_checkin.user_code')
            ->join('customers', 'customer_checkin.customer_id', '=', 'customers.id')
            ->where('users.user_code', $this->user_code)
            ->where('customers.customer_name', 'LIKE', '%' . $this->search . '%')
            ->select(
                'users.name as name',
                'customers.customer_name AS customer_name',
                'customer_checkin.start_time AS start_time',
                'customer_checkin.stop_time AS stop_time',
                DB::raw("DATE_FORMAT(customer_checkin.updated_at, '%d/%m/%Y') as formatted_date"),
                DB::raw('TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time) AS duration')
            )
            ->orderBy('formatted_date', 'DESC')
            ->paginate($this->perPage);
        return view('livewire.visits.users.view', [
            'visits' => $visits
        ]);
    }
}
