<?php

namespace App\Http\Livewire\Territory\Region;

use App\Models\customers;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 40;
   public $sortField = 'id';
   public $sortAsc = true;
   public function render()
   {
      $regions = Region::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);
      $customer_counts =customers::where('status','=','Active')->get();
      return view('livewire.territory.region.dashboard', [
         'regions' => $regions,
         'customer_counts' =>$customer_counts
      ]);
   }
}
