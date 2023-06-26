<?php

namespace App\Http\Livewire\Visits;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\visitschedule;


class Index extends Component
{
    protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
    public function render()
    {
        return view('livewire.visits.index',[
            'visits' => $this->data()
        ]);
    }
    public function data()
   {
      $query = visitschedule::all();
      if (!is_null($this->start)) {
         if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
            $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
         } else {
            if (is_null($this->end)) {
               $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
         }
      }

      return $query;
   }
}
