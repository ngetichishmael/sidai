<?php

namespace App\Http\Livewire\Routes;

use App\Models\Area;
<<<<<<< HEAD
use App\Models\customer\customers;
use Livewire\Component;
=======

use App\Models\customer\customers;
use Livewire\Component;

>>>>>>> 837ec735f3133d5de09060af882ae7fa9d5c3c36

class Customerselect extends Component
{
    public $customer_count = 0;
    public $route_id = 0;
    public $customer;
    public function render()
    {
        $routes = Area::all();
        return view('livewire.routes.customerselect',[
            'routes' => $routes,
            'customers' => $this->customer()
         ]);
         
    }
    public function customer()
    {
        $customers = customers::where('route_code', $this->route_id)->get();
        $this->customer_count = count($customers);
        return $customers;
    }

}
