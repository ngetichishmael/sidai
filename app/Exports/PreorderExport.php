<?php

namespace App\Exports;

use App\Models\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class PreorderExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.preorders', [
            'preorders' => Orders::with('User', 'Customer')->where('order_type', 'Pre Order')->get()
         ]);

    }
}
