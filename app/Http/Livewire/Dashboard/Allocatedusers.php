<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Allocatedusers extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
    public function render()
    {
       $salesPerson = User::where('business_code', Auth::user()->business_code)
          ->pluck('name', 'user_code')
          ->prepend('choose', '');

       $allocationsQuery = \App\Models\inventory\allocations::join('users', 'users.user_code', '=', 'inventory_allocations.sales_person')
          ->where('inventory_allocations.business_code', Auth::user()->business_code)->whereIn('inventory_allocations.status',['Pending Delivery','Approved', 'pending','Accepted'])
          //  ->select('inventory_allocations.*', 'inventory_allocations.created_at as allocation_created_at', 'inventory_allocations.status as allocation_status', 'users.*')
          ->select(
             'inventory_allocations.*',
             'inventory_allocations.id as allocation_id',
             'inventory_allocations.created_at as allocation_created_at',
             'users.id as user_id',
             'users.name as name',
             'users.route_code'
          )
          ->groupBy('inventory_allocations.sales_person');

       $dataAccessLevel = auth()->user()->account_type;
       if (auth()->check() && $dataAccessLevel !== ['Admin', 'NSM']) {
          $allocationsQuery->where('users.route_code', auth()->user()->route_code);
       }

       $allocations = $allocationsQuery->paginate($this->perPage);
//      $allocations = [];
//      foreach ($Allallocations as $allocation) {
//         $allocations[$allocation->sales_person] = $allocation;
//      }
       $count=1;
        return view('livewire.dashboard.allocatedusers', compact('salesPerson','allocations', 'count'));
    }
}
