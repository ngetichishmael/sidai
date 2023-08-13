<?php

namespace App\Http\Livewire\Payment\Pending;

use App\Models\customers;
use App\Models\Delivery;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 5;
   public $search = '';
   public $orderBy = 'delivery.id';
   public $orderAsc = true;
   public $customer_name = null;
   public $name = null;
   public $order_code = null;
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $deliveries = Delivery::with(['Customer', 'Order', 'User'])
         ->where('delivery_status', 'pending')
         ->orWhere('delivery_status', 'Partial delivery')
         ->when($this->user->account_type === "RSM"||$this->user->account_type === "Shop-Attendee",function($query){
            $query->whereIn('customer', $this->filter());
         })
         ->orderBy('id', 'desc')
         ->paginate($this->perPage);
      return view('livewire.payment.pending.dashboard', [
         'deliveries' => $deliveries
      ]);
   }
   public function filter(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM'||!$user->account_type ==="Shop-Attendee") {
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
}
