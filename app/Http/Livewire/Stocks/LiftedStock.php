<?php

namespace App\Http\Livewire\Stocks;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class LiftedStock extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public $orderBy = 'inventory_allocations.id';
   public $orderAsc = true;
    public function render()
    {
        $lifted = DB::table('inventory_allocations')
            ->join('inventory_allocated_items', 'inventory_allocations.allocation_code', '=', 'inventory_allocated_items.allocation_code')
            ->join('product_information', 'inventory_allocated_items.product_code', '=', 'product_information.id')
            ->join('warehouse', 'product_information.warehouse_code', '=', 'warehouse.warehouse_code')
            ->join('users', 'inventory_allocations.sales_person', '=', 'users.user_code')
            ->join('regions', 'users.region_id', '=', 'regions.id')
            ->select('inventory_allocations.allocation_code as code',
                'product_information.product_name as name',
                'inventory_allocated_items.current_qty as qty',
                'inventory_allocations.updated_at as date',
                'warehouse.name as warehouse',
                'users.name as user_name','regions.name as user_region')
           ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
           ->paginate($this->perPage);

        return view('livewire.stocks.lifted-stock', [
            'lifted' => $lifted,
        ]);
    }
}
