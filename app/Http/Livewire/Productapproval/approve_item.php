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
   public $requisition_id;

   public function mount($requisitionId)
   {
      $this->requisition_id = $requisitionId;
   }

   public $selectedProducts = [];

// Modify the approve and disapprove methods to handle multiple product IDs
   public function approve()
   { dd($this->requisition_id);
      foreach ($this->selectedProducts as $productId) {
         $requisitionProduct = RequisitionProduct::find($productId);
         $requisitionProduct->update(['approval' => 1]);

         // Decrement current stock
         product_inventory::whereId($requisitionProduct->product_id)->decrement('current_stock', $requisitionProduct->quantity);
      }

      return redirect('/warehousing/approve/'.$this->requisition_id);
   }

   public function disapprove()
   {dd($this->requisition_id);
      foreach ($this->selectedProducts as $productId) {
         $requisitionProduct = RequisitionProduct::find($productId);
         $requisitionProduct->update(['approval' => 0]);

         // Increment current stock
         product_inventory::whereId($requisitionProduct->product_id)->increment('current_stock', $requisitionProduct->quantity);
      }

      return redirect('/warehousing/approve/'.$this->requisition_id);
   }

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



//   public function approve($id)
//   {
//      $re=RequisitionProduct::whereId($id)->update(
//         [
//            'approval' => 1
//         ]
//      );
//      $products = RequisitionProduct::whereId($this->product_id)->get();
//      foreach ($products as $product) {
//         product_inventory::whereId($this->product_id)->decrement(
//            'current_stock',
//            $product->quantity
//         );
//      }
//
//      return redirect('/warehousing/approve/'.$this->requisition_id);
//   }
//   public function disapprove($id)
//   {
//      $re=RequisitionProduct::whereId($id)->update(
//         [
//            'approval' => 0
//         ]
//      );
//      $products = RequisitionProduct::whereId($this->product_id)->get();
//      foreach ($products as $product) {
//         product_inventory::whereId($this->product_id)->increment(
//            'current_stock',
//            $product->quantity
//         );
//      }
//      return redirect('/warehousing/approve/'.$this->requisition_id);
//   }
}
