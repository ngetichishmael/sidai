<?php

namespace App\Http\Livewire\Productapproval;

use App\Models\products\product_inventory;
use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use Livewire\Component;

class approve_item extends Component
{

   public $product_id;

   public function render()
   {
      $products = RequisitionProduct::where('requisition_id',$this->product_id)->with('ProductInformation')->get();

      return view('livewire.productapproval.approve', [
         'products' => $products,
      ]);
   }
   public function approve()
   {
      StockRequisition::whereId($this->product_id)->update(
         [
            'status' => 'Approved'
         ]
      );
      $products = RequisitionProduct::whereId($this->product_id)->get();
      foreach($products as $product){
         product_inventory::whereId($this->product_id)->decrement(
            'current_stock',$product->quantity
         );
      }

      return redirect('/warehousing/all/stock-requisition');
   }
   public function disapprove()
   {
      StockRequisition::whereId($this->product_id)->update(
         [
            'status' => 'Disapproved'
         ]
      );
      $products = RequisitionProduct::whereId($this->product_id)->get();
      foreach($products as $product){
         product_inventory::whereId($this->product_id)->increment(
            'current_stock',$product->quantity
         );
      }

      return redirect('/warehousing/all/stock-requisition');
   }
}
