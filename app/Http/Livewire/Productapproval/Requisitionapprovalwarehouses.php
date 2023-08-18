<?php

namespace App\Http\Livewire\Productapproval;

use App\Models\warehousing;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Requisitionapprovalwarehouses extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $orderBy = 'id';
   public $orderAsc = true;
    public function render()
    {
       $warehouses = warehousing::where('business_code', Auth::user()->business_code)
          ->withCount([
             'stockRequisitions as approval_count' => function ($query) {
                $query->where('status', 'Waiting Approval');
             }
          ])
          ->orderBy($this->orderBy,$this->orderAsc ? 'asc' : 'desc')->paginate($this->perPage);
        return view('livewire.productapproval.requisitionapprovalwarehouses', compact('warehouses'));
    }
}
