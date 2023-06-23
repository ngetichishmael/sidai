<?php

namespace App\Http\Livewire\Regional;

use App\Models\User;
use App\Models\Orders;
use App\Models\Region;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    public function render()
    {
        $regions = Region::all();
        $orders = Orders::with('User', 'Customer')->where('order_type', 'Pre Order')->get();
        $usercount = User::whereIn('account_type',['Customer'])->select('account_type', DB::raw('COUNT(*) as count'))
         ->groupBy('account_type')
         ->get();
        return view('livewire.regional.index', [
            'regions' => $regions,
            'orders' => $orders,
            'usercount' =>$usercount
        ]);
    }
}
