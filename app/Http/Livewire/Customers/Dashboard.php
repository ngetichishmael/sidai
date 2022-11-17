<?php

namespace App\Http\Livewire\Customers;

use App\Exports\customers as ExportsCustomers;
use App\Models\customer\customers;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $contacts = customers::whereLike(
         [
            'customer_name',
            'phone_number',
            'address',

         ],
         $searchTerm)
         ->where('business_code', Auth::user()->business_code)
         ->whereNotNull('email')
         ->paginate($this->perPage);
      return view('livewire.customers.dashboard', [
         'contacts' => $contacts
      ]);
   }
   public function export()
   {
      return Excel::download(new ExportsCustomers, 'customers.xlsx');
   }
}