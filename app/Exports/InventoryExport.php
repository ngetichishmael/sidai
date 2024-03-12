<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InventoryExport implements FromView
{
    private $data;

   public function __construct($data)
{
   $this->data = $data;
}

    public function view(): View
    {
        $datas = $this->data;
         return view('Exports.inventory_products', [
            'products' => $datas
         ]);

    }
}
