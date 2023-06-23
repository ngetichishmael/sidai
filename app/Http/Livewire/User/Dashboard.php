<?php

namespace App\Http\Livewire\User;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public function render()
    {
        $usercount = User::whereNotNull('user_code')->select('account_type', DB::raw('COUNT(*) as count'))
         ->groupBy('account_type')
         ->get();
        return view('livewire.user.dashboard', ['usercount' => $usercount]);
    }
}
