<?php

namespace App\Http\Livewire\Visits\Users;

use App\Exports\UserVisitsExport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class View extends Component
{
    use WithPagination;
    public $start;
    public $end;
    public $user_code;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public ?string $search = null;
    public $username;
    public function render()
    {

        return view('livewire.visits.users.view', [
            'visits' => $this->data()
        ]);
    }
    public function data()
    {
        $this->username = User::where('user_code', $this->user_code)->pluck('name')->implode('');
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->start = $this->start == null ? $start_date : $this->start;
        $this->end = $this->end == null ? $end_date : $this->end;
        $visits = DB::table('users')
            ->join('customer_checkin', 'users.user_code', '=', 'customer_checkin.user_code')
            ->join('customers', 'customer_checkin.customer_id', '=', 'customers.id')
            ->whereBetween('customer_checkin.updated_at', [$this->start, $this->end])
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

        return $visits;
    }
    public function export()
    {
        return Excel::download(new UserVisitsExport($this->data()), $this->username . 'visits.xlsx');
    }
}
