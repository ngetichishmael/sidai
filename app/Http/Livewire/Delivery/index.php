<?php

namespace App\Http\Livewire\Delivery;

use App\Models\Delivery;
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

   public function render()
   {

      $searchTerm = '%' . $this->search . '%';
      $deliveries = Delivery::where('delivery_status', 'AAA')
         ->search($searchTerm)
         ->orderBy('updated_at', 'desc')
         ->paginate($this->perPage);
      return view('livewire.delivery.index', compact('deliveries'));
   }
}
