<?php

namespace App\Http\Livewire\Reports;

use App\Exports\VansaleExport;
use App\Models\Orders;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Vansales extends Component
{
    public function render()
    {$count = 1;
        $vansales = Orders::with('User', 'Customer')->where('order_type', 'Van sales')->get();
        return view('livewire.reports.vansales', ['vansales' => $vansales, 'count' => $count]);
    }
    public function export()
   {
      return Excel::download(new VansaleExport, 'vansales.xlsx');
   }
}
