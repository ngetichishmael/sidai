<?php

namespace App\Http\Livewire\Reports;

use App\Exports\DistributorExport;
use App\Models\Orders;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Distributor extends Component
{
    protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
    public function render()
    {
        $count = 1;
        $distributors = Orders::with('User', 'Customer')->where('supplierID', '!=', '1')->where('supplierID', '!=', 'NULL')->get();
        return view('livewire.reports.distributor', ['distributors' => $distributors, 'count' => $count]);
    }
    public function export()
   {
      return Excel::download(new DistributorExport, 'Distributors.xlsx');
   }
}
