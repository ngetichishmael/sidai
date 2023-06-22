<?php

namespace App\Http\Livewire\Reports;

use App\Models\Orders;
use Livewire\Component;

class Vansales extends Component
{
    public function render()
    {$count = 1;
        $vansales = Orders::with('User', 'Customer')->where('order_status', 'Pending Delivery')->where('order_type', 'Van sales')->get();
        return view('livewire.reports.vansales', ['vansales' => $vansales, 'count' => $count]);
    }
}
