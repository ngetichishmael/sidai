<?php

namespace App\Http\Livewire\Territory\Area;

use App\Models\Area;
use App\Models\customers;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 20;
   public $sortField = 'id';
   public $sortAsc = true;
   public $searchTerm = null;
   public function render()
   {
         $areas = Area::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->when($this->searchTerm, function ($query, $searchTerm) {
               return $query->where(function ($query) use ($searchTerm) {
                  $query->where('name', 'like', '%' . $searchTerm . '%')
                     ->orWhereHas('subregion', function ($subquery) use ($searchTerm) {
                        $subquery->where('name', 'like', '%' . $searchTerm . '%');
                     });
               });
            })->paginate($this->perPage);

      $customer_counts =customers::where('status','=','Active')->get();
      return view('livewire.territory.area.dashboard', [
         'areas' => $areas,
         'customer_counts'=>$customer_counts
      ]);
   }
}
