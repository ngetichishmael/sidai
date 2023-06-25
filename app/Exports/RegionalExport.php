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

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.regional', [
            'regions' => Region::all(),
            'orders' => Orders::with('User', 'Customer')->where('order_type', 'Pre Order')->get(),
            'usercount' => User::whereIn('account_type',['Customer'])->select('account_type', DB::raw('COUNT(*) as count'))
            ->groupBy('account_type')
         ->get()
         ]);

    }
}
