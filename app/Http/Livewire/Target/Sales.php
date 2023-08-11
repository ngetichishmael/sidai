<?php

namespace App\Http\Livewire\Target;

use App\Models\SalesTarget;
use Carbon\Carbon;
use Livewire\Component;

class Sales extends Component
{
   public $perPage = 10;
   public $search = '';

    public function render()
    {
      $today = Carbon::now();
      $sales=SalesTarget::all();
        return view('livewire.target.sales',[
         'sales' => $sales,
         'today'=>$today
        ]);
    }
}
