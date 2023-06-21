<?php

namespace App\Http\Livewire\Productapproval;

use App\Models\activity_log;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use Livewire\Component;

class approve_item extends Component
{

   public $product_id;

   public function render()
   {
      $products = RequisitionProduct::where('requisition_id', $this->product_id)->with('ProductInformation')->get();

      return view('livewire.productapproval.approve', [
         'products' => $products,
      ]);
   }


   public $selectedItems = [];

   public function submitApproval()
   {
      foreach ($this->selectedItems as $itemId) {
         $this->approvestock($itemId);
      }
      $this->selectedItems = [];

      session()->flash('success', 'Selected products successfully approved!');

      return redirect()->route('inventory.approval');

   }
   public function approvestock($itemId)
   {
      $requisition_products = RequisitionProduct::where('requisition_id', number_format($itemId))->get();
      foreach ($requisition_products as $requisition_product) {
         try {
            $approveproduct = product_information::findOrFail($requisition_product);
            dd($approveproduct, $itemId, $requisition_products);
            $approveproduct->is_approved = "Yes";
            $approveproduct->save();
         } catch (\Exception $e) {
            dd($e->getMessage());
         }
      }

      $random=rand(0, 9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Stock Approval';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Stock Approved ';
      $activityLog->action = 'Stock requisition '.$requisition_products.' Successfully Approved  ';
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = '';
      $activityLog->save();
   }



   public function approve($id)
   {
      $re=RequisitionProduct::whereId($id)->update(
         [
            'approval' => 1
         ]
      );
      $products = RequisitionProduct::whereId($this->product_id)->get();
      foreach ($products as $product) {
         product_inventory::whereId($this->product_id)->decrement(
            'current_stock',
            $product->quantity
         );
      }
      $random=rand(0, 9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Stock Approval';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Stock Approved ';
      $activityLog->action = 'Stock requisition '.$re.' Successfully Approved by '.auth()->user()->name;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = '';
      $activityLog->save();

      return redirect('/warehousing/approve/'.$re->id);
   }
   public function disapprove($id)
   {
      $re=RequisitionProduct::whereId($id)->update(
         [
            'approval' => 0
         ]
      );
      $products = RequisitionProduct::whereId($this->product_id)->get();
      foreach ($products as $product) {
         product_inventory::whereId($this->product_id)->increment(
            'current_stock',
            $product->quantity
         );
      }
      $random=rand(0, 9999);
      $activityLog = new activity_log();
      $activityLog->activity = 'Stock Disapproval';
      $activityLog->user_code = auth()->user()->user_code;
      $activityLog->section = 'Stock Disapproved ';
      $activityLog->action = 'Stock requisition '.$re.' Successfully Disapproved by'.auth()->user()->name;
      $activityLog->userID = auth()->user()->id;
      $activityLog->activityID = $random;
      $activityLog->ip_address = '';
      $activityLog->save();

      return redirect('/warehousing/approve/'.$re->id);
   }
}
