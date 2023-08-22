<?php

namespace App\Http\Livewire\Reports;

use App\Exports\DistributorExport;
use App\Models\Area;
use App\Models\customer\customers;
use App\Models\Subregion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Distributor extends Component
{
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
   public $user;
   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $distributors = customers::join('orders', 'orders.customerID', '=', 'customers.id')
         ->join('areas', 'areas.id', '=', 'customers.route_code')
         ->join('subregions', 'subregions.id', '=', 'areas.subregion_id')
         ->join('regions', 'regions.id', '=', 'subregions.region_id')
         ->where('orders.supplierID', '<>', 1)
         ->whereIn('route_code', $this->filter())
         ->select(
            'regions.name as region_name',
            'customers.customer_name',
            DB::raw('count(orders.order_code) as order_count'),
            'areas.name as area_name'
         )
         ->groupBy('regions.name', 'customers.customer_name', 'areas.name')
         ->get();
      return view('livewire.reports.distributor', [
         'distributors' => $distributors
      ]);
   }
   public function filter2(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->route_code;
      if (!$user->account_type === 'RSM') {
         return $array;
      }
      $subregions = Subregion::where('region_id', $user_code)->pluck('id');
      if ($subregions->isEmpty()) {
         return $array;
      }
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if ($areas->isEmpty()) {
         return $array;
      }
      return $areas->toArray();
   }
   public function filter(): array
   {

      $array = [];
//      $user = $this->user;
      $user_code = $this->user->user_code;
      $dataAccessLevel = $this->user->roles()->pluck('data_access_level')->first();
      $subregions = Subregion::where('region_id', $this->user->region_id)->pluck('id');
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if (auth()->check() && $dataAccessLevel == 'route') {
         $customers = customers::whereIn('route', $areas)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'subregional') {
         $customers = customers::whereIn('subregion_id', $subregions)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'regional') {
         $customers = customers::where('region_id', $this->user->region_id)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'all') {
         $customers = customers::all()->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }
      else{
         return $array;
      }
//      if (!$user->account_type === 'RSM') {
//         return $array;
//      }
   }
   public function export()
   {
      return Excel::download(new DistributorExport, 'Distributors.xlsx');
   }
}
