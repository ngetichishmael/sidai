<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class UserVisitsExport implements FromView
{

    protected $array;

    public function __construct($array)
    {
        $this->array = $array;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        return view('exports.uservisits', [
            'visits' => $this->array
        ]);
    }
}
