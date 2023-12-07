<?php

namespace App\Http\Livewire\Productapproval;

use App\Models\warehouse_assign;
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
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
    public function render()
    {
       if (strcasecmp(strtolower($this->user->account_type), 'shop-attendee') == 0) {
          $check = warehouse_assign::where('manager', Auth::user()->user_code)->select('warehouse_code')->first();
          if($check)
          $warehouses = warehousing::where('warehouse_code', $check->warehouse_code)->where('business_code', Auth::user()->business_code)
             ->withCount([
                'stockRequisitions as approval_count' => function ($query) {
                   $query->where('status', 'Waiting Approval');
                }
             ])
             ->orderBy($this->orderBy,$this->orderAsc ? 'asc' : 'desc')->paginate($this->perPage);
          return view('livewire.productapproval.requisitionapprovalwarehouses', compact('warehouses'));

       }
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
