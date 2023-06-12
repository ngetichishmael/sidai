<?php

namespace App\Http\Livewire\Routes;

use App\Models\Area;
use Livewire\Component;
use App\Models\customer\customers;

class Customerselect extends Component
{
    public $route_id;
    public $customer;
    public function render()
    {
        $routes = Area::all();
        $customers = customers::where('route', $this->route_id)->get();
        return view('livewire.routes.customerselect',[
            'routes' => $routes,
            'customers' => $customers
         ]);
    }
   
}
