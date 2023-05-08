<?php

namespace App\Http\Livewire\Visits\Customer;


use App\Models\customer\checkin;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 20;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $visits = checkin::with('User', 'Customer')
         ->search($searchTerm)
         ->orderBy('id', 'desc')
         ->paginate($this->perPage);
      return view('livewire.visits.customer.dashboard', [
         'visits' => $visits,
      ]);
   }
}
