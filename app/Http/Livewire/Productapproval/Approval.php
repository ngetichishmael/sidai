<?php

namespace App\Http\Livewire\Productapproval;
use App\Models\User;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockRequisition;
use App\Models\RequisitionProduct;
use Illuminate\Support\Facades\Auth;
use App\Models\products\product_information;

class Approval extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $requisitions = StockRequisition::with('user')->withCount('RequisitionProducts', 'ApprovedRequisitionProducts')
      ->when($this->user->account_type === "RSM",function($query){
         $query->whereIn('user_id', $this->filter());
      })
       ->orderBy('id', 'DESC')->paginate($this->perPage);
      $approved = RequisitionProduct::all();

      return view('livewire.productapproval.approval', compact('requisitions'));
   }


   public function approvestock($requisition_id){
      $requisition_products = RequisitionProduct::where('requisition_id',$requisition_id)->get();
      foreach ($requisition_products as $requisition_product){
         $approveproduct = product_information::whereId($requisition_product)->first();
         $approveproduct->is_approved = "Yes";
         $approveproduct->save();
      }
      session()->flash('success', 'Product successfully Approved !');
      $random=rand(0, 9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Stock Approval';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Stock Approved ';
      $activityLog->action = 'Product '.$approveproduct->product_name .' Successfully Approved  ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = '';
      $activityLog->save();

      echo '<script>window.location.href = window.location.href;</script>';
      exit;

   }
   public function filter(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM') {
         return $array;
      }
      $regions = Region::where('id', $user_code)->pluck('id');
      if ($regions->isEmpty()) {
         return $array;
      }
      $customers = User::whereIn('region_id', $regions)->pluck('id');
      if ($customers->isEmpty()) {
         return $array;
      }
      return $customers->toArray();
   }

}
