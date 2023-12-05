<?php

namespace App\Http\Livewire\Reports;

use App\Exports\DistributorExport;
use App\Models\Area;
use PDF;
use App\Models\Orders;
use App\Models\suppliers\suppliers;
use App\Models\customer\customers;
use App\Models\Subregion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Distributor extends Component
{
   protected $paginationTheme = 'bootstrap';
   public $fromDate;
   public $toDate;
   public $orderBy = 'orders.id';
   public $orderAsc = false;
   public $perPage = 25;
   public $search = null;
   public $statusFilter = '';
   use WithPagination;
   public $user;
   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
     
      
      return view('livewire.reports.distributor', [
         'distributors' => $this->getData()
      ]);
   }
   public function getData($fromDate = null, $toDate = null) {
    $searchTerm = '%' . $this->search . '%';
    $query = suppliers::withCount('orders')
        ->withCount('OrdersDelivered')
        ->whereNotIn('name', ['sidai', 'Sidai', 'SIDAI','Sidai Warehouse']);
        
    if ($searchTerm) {
        $query->where(function ($q) use ($searchTerm) {
            $q->where('name', 'like', '%' . $searchTerm . '%');
        });
    }
    // Apply date filters if provided
    if ($fromDate) {
        $query->whereDate('created_at', '>=', $fromDate);
    }

    if ($toDate) {
        $query->whereDate('created_at', '<=', $toDate);
    }


    $data = $query->paginate($this->perPage);

    return $data;
}


  public function export()
  {
      $distributors= $this->getData();
      return Excel::download(new DistributorExport($distributors), 'distributors.xlsx');
  }
  public function exportCSV()
  {
    $distributors= $this->getData();
    return Excel::download(new DistributorExport($distributors), 'distributors.csv');
  }

  public function exportPDF()
  {
      $data = [
          'distributors' => $this->getData(),
      ];
      $pdf = PDF::loadView('Exports.distributors', $data);

      // Add the following response headers
      return response()->streamDownload(function () use ($pdf) {
          echo $pdf->output();
      }, 'distributors.pdf');
  }

}
