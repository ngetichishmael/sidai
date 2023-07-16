<?php

namespace App\Http\Livewire\Stocks;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use App\Models\inventory_allocated_items;
class LiftedStock extends Component
{
    public function render()
    {
        $lifted = DB::table('inventory_allocations')
            ->join('inventory_allocated_items', 'inventory_allocations.allocation_code', '=', 'inventory_allocated_items.allocation_code')
            ->join('product_information', 'inventory_allocated_items.product_code', '=', 'product_information.id')
            ->join('warehouse', 'product_information.warehouse_code', '=', 'warehouse.warehouse_code')
            ->join('users', 'inventory_allocations.sales_person', '=', 'users.user_code')
            ->select('inventory_allocations.allocation_code as code',
                'product_information.product_name as name',
                'warehouse.name as warehouse',
                'users.name as user_name')
            ->get();

        return view('livewire.stocks.lifted-stock', [
            'lifted' => $lifted,
        ]);
    }
}
