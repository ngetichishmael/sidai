<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class Customers extends Component
{
    public function render()
    {
        return view('livewire.reports.customers');
    }
    public function export()
   {
      return Excel::download(new CustomersExport, 'Customers.xlsx');
   }
}
