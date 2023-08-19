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
   public function render()
   {
      return view('livewire.visits.index', [
         'visits' => $this->data()
      ]);
   }
   public function data()
   {
      $query = User::withCount('Checkings')->where('route_code', '=', Auth::user()->route_code);


      return $query->get();
   }
   public function export()
   {
      return Excel::download(new VisitationExport, 'visitations.xlsx');
   }
}
