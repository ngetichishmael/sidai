<?php

namespace App\Http\Livewire\Visits\Users;

use App\Models\customer\checkin;
use App\Models\customers;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $visits = checkin::withCount(['Customer', 'Self', 'Imprompt', 'Admin'])->search($searchTerm)
         ->groupBy('user_code')
         ->paginate($this->perPage);
      return view('livewire.visits.users.dashboard', [
         'visits' => $visits
      ]);
   }
}
