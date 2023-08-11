<?php

namespace App\Http\Livewire\Delivery;

use App\Models\customers;
use App\Models\Delivery;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'delivery.id';
   public $orderAsc = true;
   public $customer_name = null;
   public $name = null;
   public $order_code = null;
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
      $deliveries = Delivery::whereIn('delivery_status', ['Delivered', 'Partial delivery'])->with('User', 'Customer')
      ->when($this->user->account_type === "RSM",function($query){
         $query->whereIn('customer', $this->filter());
      })
//         ->search($searchTerm)
         ->when($this->fromDate, function ($query) {
            return $query->whereDate('created_at', '>=', $this->fromDate);
         })
         ->when($this->toDate, function ($query) {
            return $query->whereDate('created_at', '<=', $this->toDate);
         })
         ->orderBy('updated_at', 'desc')
         ->paginate($this->perPage);
      return view('livewire.delivery.index', compact('deliveries'));
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
}
