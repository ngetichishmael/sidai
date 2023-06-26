<?php

namespace App\Http\Livewire\Reports;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;

class Customers extends Component
{
    protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
    public function render()
    {
        return view('livewire.reports.customers');
    }
//     public function data()
//    {
//       $query = customers::where('user_code','!=', 'NULL')->get();
//       if (!is_null($this->start)) {
//          if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
//             $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
//          } else {
//             if (is_null($this->end)) {
//                $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
//             }
//             $query->whereBetween('created_at', [$this->start, $this->end]);
//          }
//       }

//       return $query;
//    }

    public function export()
   {
      return Excel::download(new CustomersExport, 'Customers.xlsx');
   }
}
