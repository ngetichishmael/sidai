<?php

namespace App\Http\Livewire\Inventory;

use App\Models\inventory\allocations;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Livewire\Component;
use Livewire\WithPagination;

class Allocated extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public function render()
   {
      $salesPerson = User::where('business_code',FacadesAuth::user()->business_code)->pluck('name','user_code')->prepend('choose','');
      $allocations = allocations::join('users','users.user_code','=','inventory_allocations.sales_person')
                              ->where('inventory_allocations.business_code',FacadesAuth::user()->business_code)
                              ->select('*','inventory_allocations.created_at as created_at','inventory_allocations.status as status')
                              ->paginate($this->perPage);

      return view('livewire.inventory.allocated', compact('salesPerson','allocations'));
   }
}
