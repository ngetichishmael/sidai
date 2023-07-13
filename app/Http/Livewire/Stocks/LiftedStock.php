<?php

namespace App\Http\Livewire\Stocks;

use App\Models\InventoryAllocation;
use Livewire\Component;

class LiftedStock extends Component
{
    public function render()
    {
        $lifted = InventoryAllocation::whereNotNull('inventory_allocations.allocation_code')
        ->join('users','inventory_allocations.sales_person', '=', 'users.user_code')
        ->join('inventory_allocated_items','inventory_allocations.allocation_code', '=', 'inventory_allocated_items.allocation_code')
        ->join('inventory_allocated_items','product_information.id', '=', 'inventory_allocated_items.product_code')
        ->join('warehouses','product_information.warehouse_code', '=', 'warehouses.warehouse_code')
        ->select('users.name as username','warehouses.name as warehouse')
        ->get();
        //dd($lifted);

        return view('livewire.stocks.lifted-stock',['lifted'=>$lifted]);
    }
}
