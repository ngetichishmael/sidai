<?php

namespace App\Http\Livewire\Territory\Region;

use App\Models\Area;
use App\Models\customers;
use App\Models\Region;
use App\Models\Subregion;
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
      // $customer_counts =customers::where('status','=','Active')->get();
      return view('livewire.territory.region.dashboard', [
         'regions' => $regions
      ]);
   }
   public function customers($id)
   {
      $subregions = Subregion::where('region_id', $id)->pluck('id');
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      $customers = customers::whereIn('route_code', $areas)->count();
      return $customers ?? 0;
   }
}
