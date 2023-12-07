<?php

namespace App\Http\Livewire\Visits\Users;

use App\Exports\UsersVisitsExport;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
    use WithPagination;

    public $start, $end, $perPage = 10, $search = '', $isLoading = false;
    protected $paginationTheme = 'bootstrap';

    protected $queryString = ['search', 'perPage', 'start', 'end'];

    public function render()
    {
        return view('livewire.visits.users.dashboard', [
            'visits' => $this->queryVisits(),
        ]);
    }

    public function queryVisits()
    {
        $this->isLoading = true;

        $query = User::query()
            ->leftJoin('customer_checkin', 'users.user_code', '=', 'customer_checkin.user_code')
            ->whereRaw('customer_checkin.start_time <= customer_checkin.stop_time')
            ->select('users.name', 'users.user_code',
                DB::raw('SUM(DATE(customer_checkin.updated_at) = CURDATE()) as today_count'),
                DB::raw('COUNT(customer_checkin.id) as visit_count'),
                DB::raw('SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time)))) as average_time'),
                DB::raw('MAX(customer_checkin.created_at) as last_visit_date'))
            ->where('users.name', 'like', '%' . $this->search . '%')
            ->groupBy('users.name', 'users.user_code')
            ->having('visit_count', '>', 0);

        $this->applyDateFilters($query);

        return $query->orderByDesc('last_visit_date')->paginate($this->perPage);
    }

    private function applyDateFilters($query)
    {
        if ($this->start && $this->end) {
            $query->whereBetween('customer_checkin.created_at', [$this->start, $this->end]);
        } elseif ($this->start) {
            $query->where('customer_checkin.created_at', '>=', $this->start);
        } elseif ($this->end) {
            $query->where('customer_checkin.created_at', '<=', $this->end);
        }
    }

    public function export()
    {
        $data = $this->queryVisits()->items();

        return Excel::download(new UsersVisitsExport($data), 'SA_Visits_Summary.xlsx');
    }
}
