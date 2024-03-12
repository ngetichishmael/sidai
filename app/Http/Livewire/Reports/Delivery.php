<?php

namespace App\Http\Livewire\Reports;

use Carbon\Carbon;
use App\Models\Area;
use App\Models\Orders;
use Livewire\Component;
use App\Models\customers;
use App\Models\Subregion;
use Livewire\WithPagination;
use App\Exports\DeliveryExport;
use App\Models\suppliers\suppliers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class Delivery extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'id';
   public $orderAsc = true;
   public $fromDate;
   public $toDate;

   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }

   public function render()
   {
      $count = 1;
      $searchTerm = '%' . $this->search . '%';

      $sidai=suppliers::find(1);
      $deliveries = Orders::with('Customer', 'user', 'distributor')
         ->where('order_status','=', 'Pending Delivery')
         ->orWhere('order_status','=','DELIVERED')
         // ->when($this->user->account_type === "RSM"||$this->user->account_type === "Shop-Attendee",function($query){
         //    $query->whereIn('customerID', $this->filter());
         // })
         ->where(function ($query) use ($sidai) {
               $query->whereNull('supplierID')
                  ->orWhere('supplierID', '')
                  ->orWhere(function ($subquery) use ($sidai) {
                     if ($sidai !== null) {
                        $subquery->where('supplierID', 1);
                     }
                  });
         })
         ->where('order_type','=','Pre Order')
         ->where(function ($query) use ($searchTerm) {
            $query->whereHas('Customer', function ($subQuery) use ($searchTerm) {
               $subQuery->where('customer_name', 'like', $searchTerm);
            })
               ->orWhereHas('User', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('name', 'like', $searchTerm);
               });
         })
         ->when($this->fromDate, function ($query) {
            $query->whereDate('created_at', '>=', $this->fromDate);
         })
         ->when($this->toDate, function ($query) {
            $query->whereDate('created_at', '<=', $this->toDate);
         })
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);
      return view('livewire.reports.delivery', [
         'deliveries' => $deliveries,
         'count' => $count
      ]);
   }
   public function data()
   {
      
      // $query = Orders::with('User', 'Customer')->whereIn('customerID', $this->filter())->where('order_status', "LIKE", '%Deliver%');
      // if (!is_null($this->start)) {
      //    if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
      //       $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
      //    } else {
      //       if (is_null($this->end)) {
      //          $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
      //       }
      //       $query->whereBetween('created_at', [$this->start, $this->end]);
      //    }
      // }

      // return $query->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
      //    ->paginate(25);
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
      $customers = customers::whereIn('route_code', $areas)->pluck('id');
      if ($customers->isEmpty()) {
         return $array;
      }
      return $customers->toArray();
   }
   public function filter(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->user_code;
      $dataAccessLevel = $user->roles()->pluck('data_access_level')->first();
      $subregions = Subregion::where('region_id', $user->region_id)->pluck('id');
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if (auth()->check() && $dataAccessLevel == 'route') {
         $customers = \App\Models\customer\customers::whereIn('route', $areas)->pluck('id');
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
         $customers = customers::where('region_id', $user->region_id)->pluck('id');
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
      return Excel::download(new DeliveryExport, 'DeliveryReport.xlsx');
   }
}
