<?php

namespace App\Http\Livewire\Checkin;

use App\Models\customer\checkin;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
 protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $search;
   public $orderBy = 'customer_checkin.id';
   public $orderAsc = false;

   public function updatingSearch()
   {
      $this->resetPage();
   }

   public function render()
   {
      $search = '%'.$this->search.'%';
      $checkins =  checkin::join('customers','customers.id','=','customer_checkin.customer_id')
                           ->join('users','users.user_code','=','customer_checkin.user_code')->
                           where(function ($query) use ($search) {
                              return $query->where('customer_name', 'like', $search)
                                            ->orWhere('name', 'like', $search);})
                           ->orderBy($this->orderBy,$this->orderAsc ? 'asc' : 'desc')
                           ->paginate($this->perPage);

      return view('livewire.checkin.index', compact('checkins'));
   }
}


