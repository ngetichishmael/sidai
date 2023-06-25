<?php

namespace App\Exports;

use App\Models\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DeliveryExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.delivery', [
            'deliveries' => Orders::with('User', 'Customer')->where('order_status', 'Delivered')->get()
         ]);

    }
}
