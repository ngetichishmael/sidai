<?php

namespace App\Http\Livewire\Productapproval;

use App\Models\products\product_information;
use App\Models\Region;
use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Unapproved extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $user;
   public $warehouse_code;

   public function __construct( $warehouse_code)
   {
      $this->user = Auth::user();
      $this->warehouse_code = $warehouse_code;
   }
   public function render()
   {
      $requisitions = StockRequisition::with('user')->withCount('RequisitionProducts', 'ApprovedRequisitionProducts')
         ->where('warehouse_code', $this->warehouse_code)
         ->where('status' , '=','Disapproved')
         ->orderBy('id', 'DESC')->paginate($this->perPage);

      return view('livewire.productapproval.approved', compact('requisitions'));
   }

}
