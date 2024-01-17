<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersVisitsExport implements FromView
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
