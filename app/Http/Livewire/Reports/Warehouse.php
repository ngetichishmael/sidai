<?php

namespace App\Http\Livewire\Reports;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\warehousing;
use Livewire\WithPagination;
use App\Exports\WarehouseExport;
use App\Models\Area;
use App\Models\customer\customers;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use App\Models\Subregion;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Warehouse extends Component
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
      $count = 1;
      return view('livewire.reports.warehouse', [
         'warehouses' => $this->data(),
         'count' => $count
      ]);
   }
   public function data()
   {
      $query = warehousing::with('manager')->withCount('Products')->whereNotNull('warehouse_code')->get();
      if (!is_null($this->start)) {
         if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
            $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
         } else {
            if (is_null($this->end)) {
               $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
         }
      }

      return $query;
   }
   public function export()
   {
      return Excel::download(new WarehouseExport, 'warehouses.xlsx');
   }
   public function allocated($warehouse_code)
   {
      $product_informations = product_information::where('warehouse_code', $warehouse_code)->select('id')->get();
      $query = Order_items::query();

      if ($this->user->account_type === 'RSM') {
         $query->whereIn('order_code', $this->filter());
      }
      $query->whereIn('productID', $product_informations)->sum('quantity');
      return $order_items ?? 0;
   }
   public function filter(): array
   {
      $array = [];
      $user = Auth::user();
      $user_code = $user->route_code;
      if ($user->account_type !== 'RSM') {
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
      $customers = customers::whereIn('route_code', $areas)->pluck('id');
      if ($customers->isEmpty()) {
         return $array;
      }
      $orders = Orders::whereIn('customerID', $customers)->pluck('order_code');
      if ($orders->isEmpty()) {
         return $array;
      }
      return $orders->toArray();
   }
}
