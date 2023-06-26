<?php

namespace App\Http\Livewire\Supplier;

use App\Models\suppliers\suppliers;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $suppliers = suppliers::where('status', 'Active')
         ->OrderBy('suppliers.id', 'DESC')
         ->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', $searchTerm)
               ->orWhere('email', 'like', $searchTerm)
               ->orWhere('phone_number', 'like', $searchTerm);
         })
         ->paginate(15);
      return view('livewire.supplier.dashboard', compact('suppliers'));
   }
}
