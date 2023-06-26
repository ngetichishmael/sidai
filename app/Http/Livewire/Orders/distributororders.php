<?php

namespace App\Http\Livewire\Orders;

use App\Exports\OrdersExport;
use App\Models\suppliers\suppliers;
use Livewire\Component;
use App\Models\Orders;
use Livewire\WithPagination;
use Auth;
use Maatwebsite\Excel\Facades\Excel;


class distributororders extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'orders.id';
   public $orderAsc = false;
   public $customer_name = null;
   public $statusFilter = '';


   public $fromDate;
   public $toDate;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
      $pendingorders = Orders::with('Customer', 'user', 'distributor')
         ->where(function ($query) use ($sidai) {
            $query->whereNotNull('supplierID')
               ->where('supplierID', '!=', '')
               ->where('supplierID', '!=', $sidai->id);
         })
         ->where('order_type','=','Pre Order')
         ->where(function ($query) use ($searchTerm) {
            $query->whereHas('Customer', function ($subQuery) use ($searchTerm) {
               $subQuery->where('customer_name', 'like', $searchTerm);
            })
               ->orWhereHas('User', function ($subQuery) use ($searchTerm) {
                  $subQuery->where('name', 'like', $searchTerm);
               })
               ;
         })
         ->when($this->statusFilter, function ($query) {
            $query->where('order_status', $this->statusFilter);
         })
         ->when($this->fromDate, function ($query) {
            $query->whereDate('created_at', '>=', $this->fromDate);
         })
         ->when($this->toDate, function ($query) {
            $query->whereDate('created_at', '<=', $this->toDate);
         })
         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);

      return view('livewire.orders.distributororders', compact('pendingorders'));
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
