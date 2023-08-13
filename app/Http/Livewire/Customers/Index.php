<?php

namespace App\Http\Livewire\Customers;

use App\Models\Area;
use App\Models\customer\customers;
use App\Models\Subregion;
use Illuminate\Contracts\Container\Container;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
   public $perPage = 10;
   public $search = '';
   public $orderBy = 'customers.id';
   public $orderAsc = false;
   public $customer_name = null;
   public $user;
//   public function __invoke()
//   {
//      $this->user = Auth::user();
//   }
   public function __invoke(Container $container, Route $route)
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $contacts = customers::search($this->search);
      if ($this->user->user_code == "RSM") {
         $contacts->whereIn('id', $this->filter());
      }
      $contacts->select('*', 'customers.id as customerID', 'customers.created_at as date_added', 'business.business_code as business_code', 'customers.business_code as business_code', 'customers.email as customer_email', 'customers.phone_number as phone_number')
         ->orderBy('customers.id', 'DESC')
         ->paginate($this->perPage);

      $count = 1;

      return view('livewire.customers.index', compact('contacts', 'count'));
   }
   public function filter(): array
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
}
