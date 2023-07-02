<?php

namespace App\Http\Livewire\Visits;

use App\Models\User;
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
      // if (!is_null($this->start)) {
      //    if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
      //       $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
      //    } else {
      //       if (is_null($this->end)) {
      //          $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
      //       }
      //       $query->whereBetween('created_at', [$this->start, $this->end]);
      //    }
      // }

      return $query->get();
   }
}
