<?php

namespace App\Http\Livewire\Stocks;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\InventoryAllocation;

class LiftedStock extends Component
{
    public function render()
    {
        // $lifted = DB::table('inventory_allocations')
        //     ->join('product_information', 'inventory_allocations.allocation_code', '=', 'product_information.allocation_code')
        //     ->join('warehouse', 'product_information.warehouse_code', '=', 'warehouse.warehouse_code')
        //     ->join('users', 'inventory_allocations.sales_person', '=', 'users.user_code')
        //     ->select('inventory_allocations.allocation_code', 'product_information.product_name', 'warehouse.name', 'users.name as user_name')
        //     ->get();
            

        return view('livewire.stocks.lifted-stock');
    }
}
