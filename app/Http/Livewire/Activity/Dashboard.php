<?php

namespace App\Http\Livewire\Activity;

use App\Models\activity_log;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;

   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public $sortField = 'created_at';
   public $sortAsc = true;
   public ?string $search = null;
   public ?string $startDate = null;
   public ?string $endDate = null;


   public function render()
{
    $searchTerm = '%' . $this->search . '%';
    $activities = activity_log::with('user')
        ->where(function ($query) use ($searchTerm) {
            $query->where('user_code', 'like', $searchTerm)
                ->orWhere('activity', 'like', $searchTerm)
                ->orWhere('action', 'like', $searchTerm)
                ->orWhere('section', 'like', $searchTerm);
        })
        ->orderBy($this->sortField, $this->sortAsc ? 'desc' : 'asc')
        ->paginate($this->perPage);

    return view('livewire.activity.dashboard', [
        'activities' => $activities
    ]);
}
}
