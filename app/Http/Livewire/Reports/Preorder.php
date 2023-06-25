<?php

namespace App\Http\Livewire\Reports;

use App\Exports\PreorderExport;
use App\Models\Orders;
use Carbon\Carbon;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Preorder extends Component
{
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
         if (Carbon::parse($this->start)->isSameDay(Carbon::parse($this->end))) {
            $query->where('created_at', '=', $this->start);
         } else {
            if (is_null($this->end)) {
               $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
         }
      }
      return $query->get();
   }
   public function export()
   {
      return Excel::download(new PreorderExport, 'preorders.xlsx');
   }
}
