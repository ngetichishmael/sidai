<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\inventory\items as InventoryItems;
use App\Models\products\product_information;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Livewire\Component;
use Livewire\WithPagination;

class Allocations extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;

   public $allocationCode;

   public function mount($allocation_code)
   {
      $this->allocationCode = $allocation_code;
   }

   public function render()
   {
      $allocationCode=$this->allocationCode;
      $products = product_information::where('business_code', Auth::user()->business_code)->get();
      $allocatedItems = InventoryItems::join('product_information', 'product_information.id', '=', 'inventory_allocated_items.product_code')
         ->where('inventory_allocated_items.business_code', Auth::user()->business_code)
         ->where('allocation_code', $allocationCode)
         ->orderby('inventory_allocated_items.id', 'desc')
         ->get();
      return view('livewire.dashboard.allocations', compact('products', 'allocatedItems', 'allocationCode'));
    }
}
