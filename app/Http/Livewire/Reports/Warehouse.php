<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use App\Models\warehousing;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\WarehouseExport;

class Warehouse extends Component
{
    public function render()
    {$count = 1;
        $warehouses = warehousing::whereNotNull('warehouse_code')->get();
        return view('livewire.reports.warehouse', ['warehouses' => $warehouses, 'count' => $count]);
    }
    public function export()
   {
      return Excel::download(new WarehouseExport, 'warehouses.xlsx');
   }
}
