<?php

namespace App\Http\Livewire\Customers;

use Livewire\Component;
use App\Models\customer\customers;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
   use WithPagination;
   public $perPage = 10;
   public $search = '';
   public function render()
   {
      $contacts = customers::search($this->search)
         ->join('business', 'business.business_code', '=', 'customers.business_code')
         ->where('customers.business_code', Auth::user()->business_code)
         ->select('*', 'customers.id as customerID', 'customers.created_at as date_added', 'business.business_code as business_code', 'customers.business_code as business_code', 'customers.email as customer_email', 'customers.phone_number as phone_number')
         ->OrderBy('customers.id', 'DESC')
         ->simplePaginate($this->perPage);
      $count = 1;

      return view('livewire.customers.index', compact('contacts', 'count'));
   }
}
