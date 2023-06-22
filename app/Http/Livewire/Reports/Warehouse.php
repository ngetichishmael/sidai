<?php

namespace App\Http\Livewire\Reports;

use Livewire\Component;
use App\Models\warehousing;

class Warehouse extends Component
{
    public function render()
    {$count = 1;
        $warehouses = warehousing::whereNotNull('warehouse_code')->get();
        return view('livewire.reports.warehouse', ['warehouses' => $warehouses, 'count' => $count]);
    }
}
