<?php

namespace App\Http\Livewire\Supplier;

use App\Models\customers;
use App\Models\suppliers\suppliers;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;
   public function render()
   {
      $customers = customers::where('customer_group', 'Distributor')->get();

      foreach ($customers as $customer) {
         $existingSupplier = suppliers::where('customer_id', $customer->id)->where('name', $customer->customer_name)->first();
         if (!$existingSupplier) {
            $supplier = new suppliers();
            $supplier->email = $customer->email;
            $supplier->name = $customer->customer_name;
            $supplier->phone_number = $customer->phone_number;
            $supplier->telephone = $customer->telephone ?? $customer->phone_number;
            $supplier->status = "Active";
            $supplier->customer_id = $customer->id;
            $supplier->business_code = $customer->created_by;
            $supplier->save();
         }
      }
      $searchTerm = '%' . $this->search . '%';
      $suppliers = suppliers::where('status', 'Active')
         ->OrderBy('suppliers.id', 'DESC')
         ->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', $searchTerm)
               ->orWhere('email', 'like', $searchTerm)
               ->orWhere('phone_number', 'like', $searchTerm);
         })
         ->paginate(15);
      return view('livewire.supplier.dashboard', compact('suppliers'));
   }
}
