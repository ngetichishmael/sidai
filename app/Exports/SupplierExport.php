<?php

namespace App\Exports;

use App\Models\Orders;
use App\Models\suppliers\suppliers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SupplierExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.supplier', [
            'suppliers' => suppliers::withCount('Orders')->get()
         ]);

    }
}
