<?php

namespace App\Exports;

use App\Models\Orders;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DistributorExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.distributors', [
            'distributors' => Orders::with('User', 'Customer')->where('supplierID', '!=', '1')->where('supplierID', '!=', 'NULL')->get()
         ]);

    }
}
