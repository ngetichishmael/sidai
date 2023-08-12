<?php

namespace App\Http\Livewire\Regionselect;

use App\Models\Region;
use App\Models\Subregion;
use Livewire\Component;

class Dynamicselect extends Component
{
    public $region_id;
    public $subregion_id;

    public function render()
    {
       $regions = Region::all();
       $subregions = Subregion::where('region_id', $this->region_id)->get();
        return view('livewire.regionselect.dynamicselect',[
            'regions' => $regions,
            'subregions' => $subregions
         ]);
    }
    public function updatedRegionId($value)
   {
      $this->subregion_id = null;
   }
}
