<?php

namespace App\Http\Livewire\Creditors;

use App\Models\customers;
use Livewire\Component;

class View extends Component
{
    public $customer_id;
    public function render()
    {
        $customer = customers::with('Wallet')->whereId($this->customer_id)->first();
        return view('livewire.creditors.view', [
            'customer' => $customer,
            'customer_id' => $this->customer_id,
        ]);
    }
}
