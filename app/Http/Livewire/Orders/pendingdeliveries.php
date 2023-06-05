<?php

namespace App\Http\Livewire\Orders;

use App\Exports\OrdersExport;
use Livewire\Component;
use App\Models\Orders;
use Livewire\WithPagination;
use Auth;
use Maatwebsite\Excel\Facades\Excel;

class pendingdeliveries extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public $orderBy = 'orders.id';
   public $orderAsc = false;
   public $customer_name = null;


   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $orders =  Orders::with('Customer', 'user')->where('order_status' ,'=','Pending Delivery')
         ->search($searchTerm)
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
