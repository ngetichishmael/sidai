<?php

namespace App\Http\Livewire\Visits;

use App\Exports\VisitationExport;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      return view('livewire.visits.index', [
         'visits' => $this->data()
      ]);
   }
   public function data()
   {
      $dataAccessLevel = $this->user->roles()->pluck('data_access_level')->first();
      if (auth()->check() && $dataAccessLevel == 'regional') {
         $query = User::withCount('Checkings')->where('region_id', '=', Auth::user()->region_id);
      }
      else if (auth()->check() && $dataAccessLevel == 'all') {
         $query = User::withCount('Checkings');
      }
      return $query->get();
   }
   public function export()
   {
      return Excel::download(new VisitationExport, 'visitations.xlsx');
   }
}
