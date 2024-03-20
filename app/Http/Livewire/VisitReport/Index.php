<?php

namespace App\Http\Livewire\VisitReport;

use App\Models\VisitForm;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'status';
   public $orderAsc = true;
   public $name = null;
   public $order_code = null;
   public $start;
   public $end;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $forms=VisitForm::when($this->start, function ($query) {
         return $query->whereDate('created_at', '>=', $this->start);
      })
         ->when($this->end, function ($query) {
            return $query->whereDate('created_at', '<=', $this->end);
         })
         ->orderBy('updated_at', 'desc')
         ->paginate($this->perPage);


        return view('livewire.visit-report.index', compact('forms'));
    }
}
