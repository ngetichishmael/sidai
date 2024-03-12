<?php

namespace App\Exports;

use App\Models\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class OrdersExport implements FromView
{
    private $filters;

   public function __construct($filters)
{
   $this->filters = $filters;
}

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
        $orders = $this->filters;
         return view('Exports.orders', [
            'invoices' => $orders,
         ]);

    }
}
