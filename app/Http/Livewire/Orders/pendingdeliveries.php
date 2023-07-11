<?php

namespace App\Http\Livewire\Orders;

use App\Models\Orders;
use App\Models\Region;
use Livewire\Component;
use App\Models\Delivery;
use App\Models\customers;
use Livewire\WithPagination;
use App\Exports\OrdersExport;
use App\Models\suppliers\suppliers;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

class pendingdeliveries extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'delivery.id';
   public $orderAsc = false;
   public $customer_name = null;
   public $fromDate;
   public $toDate;

   protected $queryString = ['search', 'fromDate', 'toDate'];
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $sidai=suppliers::where('name', 'Sidai')->first();
      $orders =  Delivery::whereNotIn('delivery_status', ['Pending Delivery', 'Partial delivery'])
         ->where(function ($query) use ($sidai) {
            $query->whereHas('Order', function ($subQuery) use ($sidai) {
               $subQuery->whereNull('supplierID')
                  ->orWhere('supplierID', '')
                  ->orWhere('supplierID', 1);
            })->whereHas('Order', function ($subQuery) {
               $subQuery->where('order_type', 'Pre Order');
            });
         })
         ->with('Customer', 'User', 'Order', 'DeliveryItems')
         ->when($this->user->account_type === "RSM",function($query){
            $query->whereIn('customer', $this->filter());
         })
         ->where(function ($query) use ($searchTerm) {
            $query->whereHas('Customer', function ($subQuery) use ($searchTerm) {
               $subQuery->where('customer_name', 'like', $searchTerm);
            })
               ->orWhereHas('User', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('name', 'like', $searchTerm);
               })
               ->orWhereHas('Order', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('order_code', 'like', $searchTerm);
               });
         })
         ->when($this->fromDate, function ($query) {
            return $query->whereDate('created_at', '>=', $this->fromDate);
         })
         ->when($this->toDate, function ($query) {
            return $query->whereDate('created_at', '<=', $this->toDate);
         })
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);
         // dd($orders);
      return view('livewire.orders.pendingdeliveries', compact('orders'));
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
      $customers = customers::whereIn('region_id', $regions)->pluck('id');
      if ($customers->isEmpty()) {
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
