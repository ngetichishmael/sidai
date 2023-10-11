<?php

namespace App\Http\Livewire\Supplier;

use App\Exports\SupplierExport;
use App\Models\customers;
use App\Models\suppliers\suppliers;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class index extends Component
{
   use WithPagination;
   public $perPage = 15;
   public $search = '';
   public $start;
   public $end;

   use WithPagination;
   protected $paginationTheme = 'bootstrap';
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
      return view('livewire.supplier.index', [
         'suppliers' => $this->data()
      ]);
   }
   public function data()
   {
      $searchTerm = '%' . $this->search . '%';
      $query = suppliers::withCount('Orders');
      if (!is_null($this->start)) {
         if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
            $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
         } else {
            if (is_null($this->end)) {
               $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
         }
      }

      return $query->paginate(10)->where(function ($query) use ($searchTerm) {
         $query->where('name', 'like', $searchTerm)
            ->orWhere('email', 'like', $searchTerm);
      });
   }
   public function export()
   {
      return Excel::download(new SupplierExport, 'Suppliers.xlsx');
   }
}
