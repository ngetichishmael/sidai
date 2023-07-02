<?php

namespace App\Http\Livewire\Reports;

use App\Exports\DistributorExport;
use App\Models\Area;
use App\Models\customer\customers;
use App\Models\Orders;
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
   public function filter(): array
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
   public function export()
   {
      return Excel::download(new DistributorExport, 'Distributors.xlsx');
   }
}
