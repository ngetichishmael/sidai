<?php

namespace App\Http\Livewire\Reports;

use App\Exports\PreorderExport;
use App\Models\Orders;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Preorder extends Component
{
    public function render()
    {
        $count = 1;
        $preorders = Orders::with('User', 'Customer')->where('order_type', 'Pre Order')->get();
        return view('livewire.reports.preorder', ['preorders' => $preorders, 'count' => $count]);
    }
    public function export()
   {
      return Excel::download(new PreorderExport, 'preorders.xlsx');
   }
}
