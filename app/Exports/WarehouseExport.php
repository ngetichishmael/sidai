<?php

namespace App\Exports;

use App\Models\warehousing;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class WarehouseExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.warehousing', [
            'warehouses' => warehousing::whereNotNull('warehouse_code')->get()
         ]);

    }
}
