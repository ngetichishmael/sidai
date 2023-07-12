<?php

namespace App\Http\Livewire\Stocks;

use App\Models\InventoryAllocation;
use Livewire\Component;

class LiftedStock extends Component
{
    public function render()
    {
        $lifted = InventoryAllocation::all();
        return view('livewire.stocks.lifted-stock',['lifted'=>$lifted]);
    }
}
