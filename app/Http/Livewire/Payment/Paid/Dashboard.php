<?php

namespace App\Http\Livewire\Payment\Paid;

use App\Models\customers;
use App\Models\Delivery;
use App\Models\Region;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\order_payments;
use Livewire\WithPagination;

class Dashboard extends Component
{

   use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $fromDate;
    public $toDate;

    public $paymentMethod = 'PaymentMethods.Mpesa';

    public function render()
    {
        $payments = order_payments::join('orders', 'orders.order_code', '=', 'order_payments.order_id')
            ->join('customers', 'customers.id', '=', 'orders.customerID')
            ->join('users', 'customers.created_by', '=', 'users.id')
            ->where('payment_method', $this->paymentMethod)
            ->select(
                'order_payments.order_id as order_id',
                'order_payments.reference_number as reference_number',
                'order_payments.payment_date as payment_date',
                'order_payments.amount as amount',
                'users.name as name',
                'customers.customer_name as customer_name',
            )
            ->when($this->fromDate, function ($query) {
                return $query->whereDate('order_payments.created_at', '>=', $this->fromDate);
            })
            ->when($this->toDate, function ($query) {
                return $query->whereDate('order_payments.created_at', '<=', $this->toDate);
            })
            ->orderBy('order_payments.updated_at', 'desc')
            ->groupBy('amount')
            ->get();
      return view('livewire.payment.paid.dashboard', [
         'payments' => $payments
      ]);
   }
   public function approve($id)
   {
      Delivery::whereId($id)->update([
         'approval' => 1
      ]);
      $this->render();
   }
   public function filter(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM'||!$user->account_type ==="Shop-Attendee") {
         return $array;
      }
      $regions = Region::where('id', $user_code)->pluck('id');
      if ($regions->isEmpty()) {
         return $array;
      }
      $customers = customers::whereIn('region_id', $regions)->pluck('id');
      if ($customers->isEmpty()) {
         return $array;
      }
      return $customers->toArray();
   }
}
