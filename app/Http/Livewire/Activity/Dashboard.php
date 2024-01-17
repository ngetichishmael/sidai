<?php

namespace App\Http\Livewire\Activity;

use App\Exports\ActivityExport;
use App\Exports\CustomersExport;
use App\Models\activity_log;
//use Barryvdh\DomPDF\PDF;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class Dashboard extends Component
{
   use WithPagination;

   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public $sortField = 'created_at';
   public $sortAsc = true;
   public ?string $search = null;
   public ?string $startDate = null;
   public ?string $endDate = null;


   public function render()
   {
      $activities = $this->getPagenatedFilteredActivities();
      return view('livewire.activity.dashboard', [
         'activities' => $activities
      ]);
   }

   public function getPagenatedFilteredActivities()
   {
      $searchTerm = '%' . $this->search . '%';

      return activity_log::with('user')
         ->where(function ($query) use ($searchTerm) {
            $query->where('user_code', 'like', $searchTerm)
               ->orWhere('activity', 'like', $searchTerm)
               ->orWhere('action', 'like', $searchTerm)
               ->orWhere('section', 'like', $searchTerm)
               ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                  $userQuery->where('name', 'like', $searchTerm);
               });
         })
         ->when($this->startDate, function ($query, $startDate) {
            $query->whereDate('created_at', '>=', $startDate);
         })
         ->when($this->endDate, function ($query, $endDate) {
            $query->whereDate('created_at', '<=', $endDate);
         })
         ->orderBy($this->sortField, $this->sortAsc ? 'desc' : 'asc')
         ->paginate($this->perPage);
   }
   public function getFilteredActivities()
   {
      $searchTerm = '%' . $this->search . '%';

      return activity_log::with('user')
         ->where(function ($query) use ($searchTerm) {
            $query->where('user_code', 'like', $searchTerm)
               ->orWhere('activity', 'like', $searchTerm)
               ->orWhere('action', 'like', $searchTerm)
               ->orWhere('section', 'like', $searchTerm)
               ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                  $userQuery->where('name', 'like', $searchTerm);
               });
         })
         ->when($this->startDate, function ($query, $startDate) {
            $query->whereDate('created_at', '>=', $startDate);
         })
         ->when($this->endDate, function ($query, $endDate) {
            $query->whereDate('created_at', '<=', $endDate);
         })
         ->orderBy($this->sortField, $this->sortAsc ? 'desc' : 'asc')
         ->get();
   }

   public function exportPDF1()
   {
      $data = $this->getFilteredActivities();
      $pdf = (new \Barryvdh\DomPDF\PDF)->loadView('livewire.activity.pdf', ['data' => $data]);
      return $pdf->download('activity_report.pdf');
   }
   public function exportPDF()
   {
      $data = [
         'activities' => $this->getFilteredActivities(),
      ];
      $pdf = PDF::loadView('Exports.activity_pdf', $data);

      // Add the following response headers
      return response()->streamDownload(function () use ($pdf) {
         echo $pdf->output();
      }, 'activity_logs.pdf');
   }
   public function exportCSV()
   {
      $filteredLogs = $this->getFilteredActivities();

      return Excel::download(new ActivityExport($filteredLogs), 'activity_report.csv');
   }

   public function exportExcel()
   {
      $filteredLogs = $this->getFilteredActivities();

      return Excel::download(new ActivityExport($filteredLogs), 'activity_report.xlsx');
   }
}
