<?php

namespace App\Http\Livewire\Target;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\SalesTarget;
use Livewire\WithPagination;

class Index extends Component
{
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
   public function render()
   {
      return view('livewire.target.index', [
         'targets' => $this->data()
      ]);
   }
   public function data()
   {
      $query = SalesTarget::all();
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

      return $query;
   }
}
