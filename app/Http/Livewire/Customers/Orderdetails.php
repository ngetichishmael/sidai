<?php

namespace App\Http\Livewire\Customers;

use App\Models\Orders;
use Livewire\Component;

class Orderdetails extends Component
{
    public $customer_id;
    public function render()
    {
        return view('livewire.customers.orderdetails', [
            'orders' => Orders::with('User')->where('customerID', $this->customer_id)->get(),
        ]);
    }
}
