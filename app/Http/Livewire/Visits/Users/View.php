<?php

namespace App\Http\Livewire\Visits\Users;

use App\Exports\UserVisitsExport;
use App\Models\User;
use Carbon\Carbon;
use App\Models\SaleReport;
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

    public function formatDuration($durationSeconds)
    {
        $days = floor($durationSeconds / (60 * 60 * 24));
        $durationSeconds %= (60 * 60 * 24);
        $hours = floor($durationSeconds / (60 * 60));
        $durationSeconds %= (60 * 60);
        $minutes = floor($durationSeconds / 60);
        $seconds = $durationSeconds % 60;

        if ($days > 0) {
            return "$days days";
        } elseif ($hours > 0) {
            return "$hours hrs";
        } elseif ($minutes > 0) {
            return "$minutes mins";
        } else {
            return "$seconds secs";
        }
    }
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
                'customer_checkin.code as code',
                'customers.customer_name AS customer_name',
                'customer_checkin.start_time AS start_time',
                'customer_checkin.stop_time AS stop_time',
                DB::raw("DATE_FORMAT(customer_checkin.updated_at, '%d/%m/%Y') as formatted_date"),
                DB::raw('TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time) AS duration'),
                DB::raw("TIME_TO_SEC(TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time)) AS duration_seconds"),
                DB::raw("DATE_FORMAT(customer_checkin.updated_at, '%d/%m/%Y') as formatted_date")
            )
            ->orderBy('formatted_date', 'DESC')
            ->paginate($this->perPage);

        return $visits;
    }
    public function getChecking($checking_code)
    {
        $result = SaleReport::where('checking_code', $checking_code)->first();

        if ($result) {
            return [
                "customer_ordered" => $result->customer_ordered,
                "outlet_has_stock" => $result->outlet_has_stock,
                "competitor_supplier" => $result->competitor_supplier,
                "likely_ordered_products" => $result->likely_ordered_products,
                "highest_sale_products" => $result->highest_sale_products,
            ];
        } else {
            // Handle the case when no matching record is found
            return [
                "customer_ordered" => null,
                "outlet_has_stock" => null,
                "competitor_supplier" => null,
                "likely_ordered_products" => null,
                "highest_sale_products" => null,
            ];
        }
    }
    public function export()
    {
        return Excel::download(new UserVisitsExport($this->data()), $this->username . 'visits.xlsx');
    }
}
