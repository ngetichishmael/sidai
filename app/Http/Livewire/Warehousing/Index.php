<?php

namespace App\Http\Livewire\Warehousing;

use Livewire\Component;
use App\Models\warehousing;
use Livewire\WithPagination;

class Index extends Component
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
      $warehouses = warehousing::with('manager', 'region', 'subregion')->withCount('productInformation')
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage);

      return view('livewire.warehousing.index', [
         'warehouses' => $warehouses,
         'searchTerm' => $searchTerm
      ]);
   }
}
