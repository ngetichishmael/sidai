<?php

namespace App\Http\Livewire\Visits;

use App\Models\User;
use App\Exports\VisitationExport;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\visitschedule;
use Illuminate\Support\Facades\Auth;

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
