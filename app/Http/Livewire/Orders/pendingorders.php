<?php

namespace App\Http\Livewire\Orders;

use App\Exports\OrdersExport;
use App\Models\customers;
use App\Models\DistributorOrderApproval;
use App\Models\Orders;
use App\Models\price_group;
use App\Models\Region;
use App\Models\suppliers\suppliers;
use App\Models\warehouse_assign;
use App\Models\warehousing;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class pendingorders extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'orders.id';
   public $orderAsc = false;
   public $customer_name = null;

   public $fromDate;
   public $toDate;

   public $user;
   public $OutletType;

   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';

      $sidai=suppliers::find(1);
      $pendingorders = Orders::with('Customer', 'user', 'distributor')
         ->whereIn('order_status',['Pending Delivery', 'Waiting Approval', ' ','Fully Approved'])
         ->when($this->user->account_type === "RSM"||$this->user->account_type === "Shop-Attendee",function($query){
            $query->whereIn('customerID', $this->filter());
         })
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
         ->when($this->OutletType, function ($query) {
            $query->whereHas('Customer', function ($subQuery) {
               $subQuery->where('price_group', $this->OutletType);
            });
         })
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);
      $outlets=price_group::all();
      return view('livewire.orders.pendingorders', compact('pendingorders', 'outlets'));
   }
   public function filter(): array
   {
      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM' || !$user->account_type ==="Shop-Attendee") {
         return $array;
      }
      if ($user->account_type ==="Shop-Attendee"){
         $warehouse=warehouse_assign::where('manager', $user->user_code)->first();
         if (empty($warehouse)) {
            return $array;
         }
         $region=warehousing::where('warehouse_code', $warehouse->warehouse_code)->pluck('region_id');
         $customers = customers::whereIn('region_id', $region)->pluck('id');
         return $customers->toArray();
      }else {
         $regions = Region::where('id', $user_code)->pluck('id');
         if (empty($regions)) {
            return $array;
         }
         $customers = customers::whereIn('region_id', $regions)->pluck('id');
         return $customers->toArray();
      }
      if (empty($customers)) {
         return $array;
      }
      return $customers->toArray();
   }
   public function export()
   {
      return Excel::download(new OrdersExport, 'orders.xlsx');
   }

   public function deactivate($id)
   {

      Orders::whereId($id)->update([
         'order_status' => 'CANCELLED',
      ]);
      $this->render();
   }
   public function activate($id)
   {
      Orders::whereId($id)->update([
         'order_status' => 'Pending Delivery',
      ]);
      $this->render();
   }
}
