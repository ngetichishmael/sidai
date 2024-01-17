<?php

namespace App\Http\Livewire\Visits\Customer;


use Livewire\Component;
use Livewire\WithPagination;
use App\Models\customer\checkin;
use App\Exports\CustomerVisitExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 20;
   public ?string $search = null;
    public $start = null;
    public $end = null;

   public function render()
   {
      $searchTerm = '%' . $this->search . '%';

        $query = checkin::with('User', 'Customer')
            ->whereHas('User')
            ->whereHas('Customer')
            ->whereLike(
                [
                    'User.name',
                    'Customer.customer_name',
                ],
                $searchTerm
            )
            ->orderBy('created_at', 'desc'); 

        if ($this->start && $this->end) {
            $query->whereBetween('created_at', [$this->start, $this->end]);
        } elseif ($this->start) {
            $query->where('created_at', '>=', $this->start);
        } elseif ($this->end) {
            $query->where('created_at', '<=', $this->end);
        }

        $visits = $query->paginate($this->perPage);
      return view('livewire.visits.customer.dashboard', [
         'visits' => $visits,
      ]);
   }
   public function exportCSV($timeInterval = null)
   {
       $currentDateTime = now()->format('F j, Y'); // Format the current date and time as "September 28, 2023"
       $fileName = "Customers_checkins - {$currentDateTime}.csv";
   
       return Excel::download(new CustomerVisitExport($timeInterval), $fileName);
   }
   

   public function exportExcel($timeInterval = null)
   {
       $currentDateTime = now()->format('F j, Y'); // Format the current date and time as "September 28, 2023"
       $fileName = "Customers_checkins - {$currentDateTime}.xlsx";
   
       return Excel::download(new CustomerVisitExport($timeInterval), $fileName);
   }
   

   public function exportPDF($timeInterval = null)
   {
       $currentDateTime = now()->format('F j, Y'); // Format the current date and time as "September 28, 2023"
       $fileName = "Customers_checkins - {$currentDateTime}.pdf";
   
       $data = $this->getExportData($timeInterval);
   
       $pdf = FacadePdf::loadView('Exports.visits', ['data' => $data, 'timeInterval' => $timeInterval]);
   
       return response()->streamDownload(function () use ($pdf) {
           echo $pdf->output();
       }, $fileName);
   }
   
   protected function getExportData($timeInterval)
   {
       $query = checkin::select('customer_checkin.*', 'users.name')
           ->join('users', 'customer_checkin.user_code', '=', 'users.user_code')
           ->orderBy('customer_checkin.user_code') // Order by user_code (users' codes) in ascending order
           ->orderBy('users.name'); // Order users' names in alphabetical order

       if ($timeInterval === 'today') {
           $query->whereDate('customer_checkin.created_at', today());
       } elseif ($timeInterval === 'yesterday') {
           $query->whereDate('customer_checkin.created_at', today()->subDay());
       } elseif ($timeInterval === 'this_week') {
           $query->whereBetween('customer_checkin.created_at', [now()->startOfWeek(), now()->endOfWeek()]);
       } elseif ($timeInterval === 'this_month') {
           $query->whereYear('customer_checkin.created_at', now()->year)->whereMonth('customer_checkin.created_at', now()->month);
       } elseif ($timeInterval === 'this_year') {
           $query->whereYear('customer_checkin.created_at', now()->year);
       }

       $checkinData = $query->get();

       return $checkinData;
   }

}
