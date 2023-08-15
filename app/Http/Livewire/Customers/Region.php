<?php

namespace App\Http\Livewire\Customers;

use App\Models\Area;
use App\Models\Region as ModelsRegion;
use App\Models\Subregion;
use Livewire\Component;

class Region extends Component
{
   public $regions;
   public $subregions;
   public $areas;

   public $region = NULL;
   public $subregion = NULL;
   public function render()
   {
      return view('livewire.customers.region',[
         'region'=>$this->regions,
         'subregion'=>$this->subregions
      ]);
   }
   public function updatedRegion($region)
   {
      $this->subregions = Subregion::where('region_id', $region)->get();
   }
   public function updatedSubregion($subregion)
   {
      $this->areas = Area::where('subregion_id', $subregion)->get();
   }
   public function mount()
   {

      $this->regions = ModelsRegion::all();
      $this->subregions = Subregion::all();
      $this->areas = Area::all();
   }
}
