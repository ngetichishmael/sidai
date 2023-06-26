<?php

namespace App\Http\Livewire\Reports;

use App\Exports\DistributorExport;
use App\Models\customer\customers;
use App\Models\Orders;
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
   public function export()
   {
      return Excel::download(new DistributorExport, 'Distributors.xlsx');
   }
}
