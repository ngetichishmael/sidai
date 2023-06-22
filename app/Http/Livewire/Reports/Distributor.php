<?php

namespace App\Http\Livewire\Reports;

use App\Models\Orders;
use Livewire\Component;

class Distributor extends Component
{
    public function render()
    {
        $count = 1;
        $distributors = Orders::with('User', 'Customer')->where('supplierID', '!=', '1')->where('supplierID', '!=', 'NULL')->get();
        return view('livewire.reports.distributor', ['distributors' => $distributors, 'count' => $count]);
    }
}
