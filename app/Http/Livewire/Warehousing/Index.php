<?php

namespace App\Http\Livewire\Warehousing;

use App\Models\Region;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      if (strcasecmp(strtolower($this->user->account_type), 'shop-attendee') == 0) {
         $check = warehouse_assign::where('manager', Auth::user()->user_code)->select('warehouse_code')->first();
         $warehouses = warehousing::where('warehouse_code', $check)->with('manager', 'region', 'subregion')->withCount('productInformation')
            ->paginate($this->perPage);
      } else{
         $warehouses = warehousing::with('manager', 'region', 'subregion')->withCount('productInformation')
            ->when($this->user->account_type === "RSM", function ($query) {
               $query->whereIn('region_id', $this->filter());
            })
            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->paginate($this->perPage);
   }
      info($warehouses);
      return view('livewire.warehousing.index', [
         'warehouses' => $warehouses,
         'searchTerm' => $searchTerm
      ]);
   }
   public function filter(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM') {
         return $array;
      }
      $regions = Region::where('id', $user_code)->pluck('id');
      if ($regions->isEmpty()) {
         return $array;
      }
      return $regions->toArray();
   }
}

