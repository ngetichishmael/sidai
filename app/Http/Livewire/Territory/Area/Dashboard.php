<?php

namespace App\Http\Livewire\Territory\Area;

use App\Models\Area;
use App\Models\customers;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Illuminate\Support\Facades\Auth;
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
      $user = Auth::user();
         $areas = Area::orderBy($this->sortField, $this->sortAsc ? 'asc' : 'desc')
            ->when($this->searchTerm, function ($query, $searchTerm) {
               return $query->where(function ($query) use ($searchTerm) {
                  $query->where('name', 'like', '%' . $searchTerm . '%')
                     ->orWhereHas('subregion', function ($subquery) use ($searchTerm) {
                        $subquery->where('name', 'like', '%' . $searchTerm . '%');
                     });
               });
            });
      if ($user->account_type ==="Shop-Attendee") {
         $warehouse = warehouse_assign::where('manager', $user->user_code)->first();
         if ($warehouse){
            $warehouse_c=warehousing::where('warehouse_code', $warehouse->warehouse_code)->first();
         }
         if ($warehouse_c){

            $areas->where('subregion_id',$warehouse_c->subregion_id);
         }
      }
           $areas ->paginate($this->perPage);

dd($areas);
      $customer_counts =customers::where('status','=','Active')->get();
      return view('livewire.territory.area.dashboard', [
         'areas' => $areas,
         'customer_counts'=>$customer_counts
      ]);
   }
}
