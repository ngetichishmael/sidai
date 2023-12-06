<?php

namespace App\Http\Livewire\Territory\SubRegion;

use App\Models\customers;
use App\Models\Subregion;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Illuminate\Support\Facades\Auth;
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
      $user = Auth::user();
      $subregions = Subregion::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc');
      if ($user->account_type ==="Shop-Attendee") {
         $warehouse = warehouse_assign::where('manager', $user->user_code)->first();
         if ($warehouse){
            $warehouse_c=warehousing::where('warehouse_code', $warehouse->warehouse_code)->first();
         }if ($warehouse_c){
            $subregions->where('region_id',$warehouse_c->region_id);
         }
      }
      $subregions->paginate($this->perPage);
      $customer_counts =customers::where('status','=','Active')->get();
      return view('livewire.territory.sub-region.dashboard', [
         'subregions' => $subregions,
         'customer_counts'=>$customer_counts
      ]);
   }
}
