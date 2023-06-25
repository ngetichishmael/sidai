<?php

namespace App\Http\Livewire\Supplier;

use App\Models\Orders;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\suppliers\suppliers;

class index extends Component
{
   public $start = null;
   public $end = null;

   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public function render()
   {
      $suppliers = suppliers::withCount('Orders')->paginate(10);
      return view('livewire.supplier.index', [
         'suppliers' => $suppliers
      ]);
   }
}
