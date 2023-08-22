<?php

namespace App\Http\Livewire\Reports;

use App\Models\Subregion;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Inventory extends Component
{
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
    public function render()
    {
        $warehouses = warehousing::whereNotNull('warehouse_code')->distinct('name')->get();
        $count = 1;
        return view('livewire.reports.inventory', ['warehouses' => $this->data(), 'count' => $count]);
    }

   public function data()
   {
      $dataAccessLevel = $this->user->roles()->pluck('data_access_level')->first();

      if (auth()->check() && $dataAccessLevel=='route') {
         $assigned = warehouse_assign::where('manager', auth()->user()->id)->first();

         if ($assigned) {
            $warehouseCode = $assigned->warehouse_code;
            $query = warehousing::where('warehouse_code', $warehouseCode)
               ->whereNotNull('warehouse_code')->distinct('name')->get();
            return $query;
         }else{
            return $query=[];
         }
      }else if(auth()->check() && $dataAccessLevel=='subregional') {
         $subregions=Subregion::where('region_id', $this->user->region_id)->get();
         if (!empty($subregions)) {
            $query = warehousing::whereIn('subregion_id', $subregions->pluck('id'))
               ->distinct('name')
               ->whereNotNull('warehouse_code')
               ->get();
            return $query;
         }else{
            return $query=[];
         }
      }
      else if(auth()->check() && $dataAccessLevel=='all') {
         $query = warehousing::whereNotNull('warehouse_code')->distinct('name')->get();
         return $query;
      }else if(auth()->check() && $dataAccessLevel=='regional') {
         $query = warehousing::where('region_id', $this->user->region_id)
            ->whereNotNull('warehouse_code')
            ->distinct('name')
            ->get();

         return $query;

      }else{
         return $query=[];
      }

   }
}
