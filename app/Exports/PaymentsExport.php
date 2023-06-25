<?php

namespace App\Exports;

use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PaymentsExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.payments', [
            'orders' => Orders::select(
                'orders.id',
                'customers.customer_name',
                'orders.order_code',
                'customers.customer_type',
                'orders.created_at',
                DB::raw('COALESCE(SUM(order_payments.amount), 0) AS total_payment')
             )
                ->join('customers', 'orders.customerID', '=', 'customers.id')
                ->leftJoin('order_payments', 'orders.order_code', '=', 'order_payments.order_id')
                ->get()
         ]);

    }
}
