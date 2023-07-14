<?php

namespace App\Http\Livewire\Warehousing;

use App\Models\Region;
use Livewire\Component;
use App\Models\customers;
use App\Models\warehousing;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

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
      $warehouses = warehousing::with('manager', 'region', 'subregion')->withCount('productInformation')
      ->when($this->user->account_type === "RSM",function($query){
         $query->whereIn('region_id', $this->filter());
      })
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage);
         

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
