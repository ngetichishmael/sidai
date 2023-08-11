<?php

namespace App\Exports;

use App\Models\Orders;
use App\Models\customers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CustomersExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.customersreports',
         [
            'customers'=>customers::has('orders')->withCount('orders')->get()
         ]);

    }
}
