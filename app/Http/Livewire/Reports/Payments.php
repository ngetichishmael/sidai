<?php

namespace App\Http\Livewire\Reports;

use Carbon\Carbon;
use App\Models\Orders;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\PaymentsExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class Payments extends Component
{
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
   public function render()
   {
      return view('livewire.reports.payments', [
         'orders' => $this->data()
      ]);
   }
   public function data()
   {
      $query = Orders::select(
         'orders.id',
         'customers.customer_name',
         'orders.order_code',
         'customers.customer_type',
         'orders.created_at',
         DB::raw('COALESCE(SUM(order_payments.amount), 0) AS total_payment')
      )
         ->join('customers', 'orders.customerID', '=', 'customers.id')
         ->leftJoin('order_payments', 'orders.order_code', '=', 'order_payments.order_id')
         ->get();
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
      return Excel::download(new PaymentsExport, 'Payments.xlsx');
   }
}
