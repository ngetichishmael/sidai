<?php

namespace App\Http\Livewire\Reports;

use App\Models\warehousing;
use Livewire\Component;

class Inventory extends Component
{
    public function render()
    {
        $warehouses = warehousing::whereNotNull('warehouse_code')->distinct('name')->get();
        $count = 1;
        return view('livewire.reports.inventory', ['warehouses' => $warehouses, 'count' => $count]);
    }
}
