<?php

namespace App\Http\Livewire\Reports;

use App\Exports\PreorderExport;
use App\Models\Orders;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Preorder extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   public function render()
   {
      $count = 1;

      return view('livewire.reports.preorder', [
         'preorders' => $this->data(),
         'count' => $count
      ]);
   }
   public function data()
   {
      $query = Orders::with('User', 'Customer')->where('order_type', 'Pre Order');
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

      return $query->paginate(7);
   }
   public function export()
   {
      return Excel::download(new PreorderExport, 'preorders.xlsx');
   }
}
