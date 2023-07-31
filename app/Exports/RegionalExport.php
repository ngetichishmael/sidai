<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Orders;
use App\Models\Region;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RegionalExport implements FromView
{
    protected $array;

    public function __construct($array)
    {
        $this->array = $array;
    }

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.regional',['regions'=> $this->array] );

    }
}
