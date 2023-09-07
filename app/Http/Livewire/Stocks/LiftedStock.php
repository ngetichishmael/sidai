<?php

namespace App\Http\Livewire\Stocks;

use App\Models\InventoryAllocation;
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
   public $fromDate;
   public $toDate;
   public $search;
   public $source ='Sidai';
    public function render()
    {
//        $lifted = DB::table('inventory_allocations')
//            ->join('inventory_allocated_items', 'inventory_allocations.allocation_code', '=', 'inventory_allocated_items.allocation_code')
//            ->join('product_information', 'inventory_allocated_items.product_code', '=', 'product_information.id')
//            ->join('warehouse', 'product_information.warehouse_code', '=', 'warehouse.warehouse_code')
//            ->join('users', 'inventory_allocations.sales_person', '=', 'users.user_code')
//            ->join('regions', 'users.region_id', '=', 'regions.id')
//            ->select('inventory_allocations.allocation_code as code',
//                'product_information.product_name as name',
//                'inventory_allocated_items.current_qty as qty',
//                'inventory_allocations.updated_at as date',
//                'inventory_allocations.distributor as distributor',
//                'warehouse.name as warehouse',
//                'users.name as user_name','regions.name as user_region')
//           ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
//           ->paginate($this->perPage);
       $lifted = InventoryAllocation::join('inventory_allocated_items', 'inventory_allocations.allocation_code', '=', 'inventory_allocated_items.allocation_code')
          ->join('product_information', 'inventory_allocated_items.product_code', '=', 'product_information.id')
          ->join('warehouse', 'product_information.warehouse_code', '=', 'warehouse.warehouse_code')
          ->join('users', 'inventory_allocations.sales_person', '=', 'users.user_code')
          ->join('regions', 'users.region_id', '=', 'regions.id')
          ->select('inventory_allocations.allocation_code as code',
             'product_information.product_name as name',
             'inventory_allocated_items.current_qty as qty',
             'inventory_allocations.updated_at as date',
             'inventory_allocations.distributor as distributor',
             'warehouse.name as warehouse',
             'users.name as user_name', 'regions.name as user_region')
          ->with('distributors')
          ->when($this->source === 'Sidai', function ($query) {
             // If source is 'Sidai', filter records where distributor is 1 or null
             return $query->where('inventory_allocations.distributor', 1)->orWhereNull('inventory_allocations.distributor');
          })
          ->when($this->source === 'Distributor', function ($query) {
             // If source is 'Distributor', filter records where distributor is not 1 or null
             return $query->where('inventory_allocations.distributor', '!=', 1)->whereNotNull('inventory_allocations.distributor');
          })
          ->when($this->fromDate, function ($query) {
             $query->whereDate('inventory_allocations.updated_at', '>=', $this->fromDate);
          })
          ->when($this->toDate, function ($query) {
             $query->whereDate('inventory_allocations.updated_at', '<=', $this->toDate);
          })
          ->when($this->search, function ($query) {
             $query->where(function ($query) {
                $query->where('regions.name', 'like', '%' . $this->search . '%')
                   ->orWhere('users.name', 'like', '%' . $this->search . '%')
                   ->orWhere('inventory_allocations.distributor', 'like', '%' . $this->search . '%')
                   ->orWhere('warehouse.name', 'like', '%' . $this->search . '%')
                   ->orWhere('product_information.product_name', 'like', '%' . $this->search . '%');
             });
          })
          ->groupBy('inventory_allocations.allocation_code')
          ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
          ->paginate($this->perPage);
        return view('livewire.stocks.lifted-stock', [
            'lifted' => $lifted,
        ]);
    }
}
