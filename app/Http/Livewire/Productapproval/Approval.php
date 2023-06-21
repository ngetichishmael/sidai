<?php

namespace App\Http\Livewire\Productapproval;

use App\Models\StockRequisition;
use Livewire\Component;
use Livewire\WithPagination;

class Approval extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public function render()
   {
      $requisitions = StockRequisition::with('user')->withCount('RequisitionProducts', 'ApprovedRequisitionProducts') ->orderBy('id', 'DESC')->paginate($this->perPage);

      return view('livewire.productapproval.approval', compact('requisitions'));
   }
}
