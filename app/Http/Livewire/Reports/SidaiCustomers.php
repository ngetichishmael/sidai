<?php

namespace App\Http\Livewire\Reports;

use App\Exports\CustomersExport;
use App\Models\customers;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SidaiCustomers extends Component
{
    protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
    public function render()
    {
        return view('livewire.reports.sidai-customers',[
            'users' =>$this->data()
        ]);
    }
    public function data()
   {
      $query = customers::has('orders')->withCount('orders')->get();
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

      return $query;
   }

    public function export()
   {
      return Excel::download(new CustomersExport, 'Customers.xlsx');
   }
}

