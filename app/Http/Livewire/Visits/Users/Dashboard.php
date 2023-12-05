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
    public $search = null;
    public $isLoading = false;
    public $selectedDate;
    public $selectedMonth;

    public function render()
    {

        return view('livewire.visits.users.dashboard', [
            'visits' => $this->data(),
        ]);
    }
    public function data()
    {
        $this->isLoading = true;

        sleep(2);

        $searchTerm = '%' . $this->search . '%';

        // Use an alias for the query to be able to reference it in the ORDER BY clause
        $query = User::leftJoin('customer_checkin', function ($join) {
            $join->on('users.user_code', '=', 'customer_checkin.user_code')
                ->whereRaw('customer_checkin.start_time <= customer_checkin.stop_time');
        })
            ->select(
                'users.name as name',
                'users.user_code as user_code',
                DB::raw('SUM(IF(DATE(customer_checkin.updated_at) = DATE("' . $this->selectedDate . '"), 1, 0)) as today_count'),
                DB::raw('COUNT(customer_checkin.id) as visit_count'),
                DB::raw('SEC_TO_TIME(AVG(TIME_TO_SEC(TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time)))) as average_time'),
                DB::raw('MAX(customer_checkin.created_at) as last_visit_date') // Use created_at for the last visit date
            )
            ->where('users.name', 'like', $searchTerm)
            ->groupBy('users.name', 'users.user_code')
            ->havingRaw('visit_count > 0'); // Only include users with completed visits

        if ($this->selectedMonth != null) {
            $query->whereYear('customer_checkin.created_at', '=', Carbon::parse($this->selectedMonth)->format('Y'))
                ->whereMonth('customer_checkin.created_at', '=', Carbon::parse($this->selectedMonth)->format('m'));
        } else {
            // Check if both start and end dates are selected
            if ($this->start != null && $this->end != null) {
                $query->whereBetween('customer_checkin.created_at', [$this->start, $this->end]);
            } elseif ($this->start != null) { // Only start date is selected
                $query->where('customer_checkin.created_at', '>=', $this->start);
            } elseif ($this->end != null) { // Only end date is selected
                $query->where('customer_checkin.created_at', '<=', $this->end);
            } else { // No date filters, use the selected date
                $query->whereBetween('customer_checkin.updated_at',
                    [Carbon::now()->startOfMonth()->format('Y-m-d')
                        , Carbon::now()->endOfMonth()->format('Y-m-d'),
                    ]);
            }
        }

        // Modify the SQL query to order by last_visit_date in descending order
        $query->orderByDesc('last_visit_date');

        $visits = $query->paginate($this->perPage);

        // Set the last_visit_time for each user based on the first record's created_at
        foreach ($visits as $visit) {
            $visit->last_visit_time = Carbon::parse($visit->last_visit_date)->format('Y-m-d H:i:s');
        }

        $this->isLoading = false;

        return $visits;
    }

    public function export()
    {
        $data = $this->data();

        // Transform the $data collection to an array for export
        $exportData = $data->map(function ($item) {
            return [
                'Sales Associate' => $item->name,
                'Visit Count' => $item->visit_count,
                'Last Visit' => $item->last_visit_date ? Carbon::parse($item->last_visit_date)->format('j M, Y') : 'N/A',
            ];
        });

        // Provide column headings for the Excel file
        $headings = [
            'Sales Associate',
            'Visit Count',
            'Last Visit',

        ];

        // Create a collection with column headings and data
        $exportData = collect([$headings])->merge($exportData);

        return Excel::download(new UsersVisitsExport($exportData), 'SA Visits Summary.xlsx');
    }

}
