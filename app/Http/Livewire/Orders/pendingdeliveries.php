<?php

namespace App\Http\Livewire\Orders;

use App\Exports\OrdersExport;
use App\Models\Delivery;
use App\Models\suppliers\suppliers;
use Livewire\Component;
use App\Models\Orders;
use Livewire\WithPagination;
use Auth;
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
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $sidai=suppliers::where('name', 'Sidai')->first();
      $orders =  Delivery::whereNotIn('delivery_status', ['Waiting acceptance', 'Partial delivery'])
         ->with('Customer', 'User', 'Order', 'DeliveryItems')
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
      return view('livewire.orders.pendingdeliveries', compact('orders'));
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
