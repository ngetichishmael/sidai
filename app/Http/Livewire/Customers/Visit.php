<?php

namespace App\Http\Livewire\Customers;

use App\Models\customer\checkin;
use Livewire\Component;

class Visit extends Component
{
    public $customer_id;
    public function render()
    {
        return view('livewire.customers.visit', [
            'checkins' => checkin::with('user')->where('customer_id', $this->customer_id)->limit(3)->get(),
        ]);
    }
}
