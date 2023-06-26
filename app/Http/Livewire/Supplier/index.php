<?php

namespace App\Http\Livewire\Supplier;

use Carbon\Carbon;
use App\Models\Orders;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\SupplierExport;
use App\Models\suppliers\suppliers;
use Maatwebsite\Excel\Facades\Excel;

class index extends Component
{
   public $start;
   public $end;

   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public function render()
   {
      return view('livewire.supplier.index', [
         'suppliers' => $this->data()
      ]);
   }
   public function data()
   {
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

      return $query->paginate(10);
   }
   public function export()
   {
      return Excel::download(new SupplierExport, 'Suppliers.xlsx');
   }
}
