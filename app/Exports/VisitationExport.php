<?php

namespace App\Exports;

use App\Models\User;
use App\Models\Orders;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;

class VisitationExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.visitation', [
            'visitations' => User::withCount('Checkings')->where('route_code', '=', Auth::user()->route_code)->get()
         ]);

    }
}
