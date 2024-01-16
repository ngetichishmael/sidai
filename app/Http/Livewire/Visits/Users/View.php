<?php

namespace App\Http\Livewire\Visits\Users;

use App\Exports\UserVisitsExport;
use App\Exports\CustomerViewVisitExport;
use App\Models\FormResponse;
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

      $query = DB::table('users')
         ->where('users.user_code', $this->user_code)
         ->where('customers.customer_name', 'LIKE', '%' . $this->search . '%')
         ->join('customer_checkin', 'users.user_code', '=', 'customer_checkin.user_code')
         ->leftJoin('customers', 'customer_checkin.customer_id', '=', 'customers.id')
         ->whereRaw('customer_checkin.start_time <= customer_checkin.stop_time') // Condition to ensure start_time <= stop_time
         ->select(
            'customer_checkin.id as id',
            'customer_checkin.code as code',
            'users.name as name',
            'customers.customer_name AS customer_name',
            DB::raw("DATE_FORMAT(customer_checkin.start_time, '%h:%i %p') AS start_time"),
            DB::raw("DATE_FORMAT(customer_checkin.stop_time, '%h:%i %p') AS stop_time"),
            DB::raw("TIME_TO_SEC(TIMEDIFF(customer_checkin.stop_time, customer_checkin.start_time)) AS duration_seconds"),
            DB::raw("DATE_FORMAT(customer_checkin.updated_at, '%d/%m/%Y') as formatted_date")
         )
         ->orderBy('customer_checkin.created_at', 'DESC');
      if ($this->start != null) {
         // If end date is null, set it to the end of the current month
         $this->end = $this->end ?? Carbon::now()->endOfMonth()->format('Y-m-d');

         if (Carbon::parse($this->start)->isSameDay(Carbon::parse($this->end))) {
            $query->where('customer_checkin.updated_at', 'LIKE', "%" . $this->start . "%");
         } else {
            $query->whereBetween('customer_checkin.updated_at', [$this->start, $this->end]);
         }
      }

      $visits = $query->paginate($this->perPage);
      return $visits;
   }
   public function getChecking($checking_code)
   {
      $result = FormResponse::where('checking_code', $checking_code)->first();

      if ($result) {
         return [
            "interested_in_new_order" => $result->interested_in_new_order,
            "pricing_accuracy" => $result->pricing_accuracy,
            "progress_status" => $result->progress_status,
            "product_visible" => $result->product_visible,
            "image" => $result->image,

         ];
      } else {
         // Handle the case when no matching record is found
         return [
            "interested_in_new_order" => null,
            "pricing_accuracy" => null,
            "progress_status" => null,
            "product_visible" => null,
            "image" => null,
         ];
      }
   }

   public function export()
   {
      // Fetch filtered data using the data method
      $data = $this->data();

      // Transform the $data collection to an array for export
      $exportData = $data->map(function ($item) {
         return [
            'Sales Associate' => $item->name,
            'Customer Name' => $item->customer_name,
            'Start Time' => $item->start_time,
            'Stop Time' => $item->stop_time,
            'Duration' => $this->formatDuration($item->duration_seconds),
            'Date' => $item->formatted_date,
         ];
      });

      // Provide column headings for the Excel file
      $headings = [
         'Sales Associate',
         'Customer Name',
         'Start Time',
         'Stop Time',
         'Duration',
         'Date',
      ];

      // Add the username as the first row in the exported data
      $exportData->prepend([$this->username]);

      // Create a collection with column headings and data
      $exportData = collect([$headings])->merge($exportData);

      return Excel::download(new CustomerViewVisitExport($exportData, $this->username), 'Visits_' . $this->username . '.xlsx');
   }
}
