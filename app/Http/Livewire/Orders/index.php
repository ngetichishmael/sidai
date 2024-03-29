<?php

namespace App\Http\Livewire\Orders;

use App\Exports\OrdersExport;
use App\Models\Orders;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 5;
   public ?string $search = null;
   public $orderBy = 'orders.id';
   public $orderAsc = false;
   public $customer_name = null;


   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $orders =  Orders::with('Customer', 'user')
         ->search($searchTerm)
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);

      return view('livewire.orders.index', compact('orders'));
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
