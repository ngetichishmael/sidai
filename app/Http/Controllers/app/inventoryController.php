<?php

namespace App\Http\Controllers\app;

use App\Models\RequisitionProduct;
use App\Models\StockRequisition;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\inventory\allocations;

class inventoryController extends Controller
{
   //allocated
   public function allocated(){
      return view('app.inventory.allocated');
   }

   //allocate user
   public function allocate_user(Request $request){
      $code = Str::random(20);
      $item = new allocations;
      $item->business_code = Auth::user()->business_code;
      $item->allocation_code = $code;
      $item->sales_person = $request->sales_person;
      $item->date_allocated = date("Y-m-d");
      $item->status = 'Waiting acceptance';
      $item->created_by = Auth::user()->user_code;
      $item->save();

      Session()->flash('success','Allocate products to sales person');

      return redirect()->route('inventory.allocate.items',$code);
   }

   //allocate items
   public function allocate_items($code){
      return view('app.inventory.allocate_items', compact('code'));
   }
   public function approval($warehouse_code)
   {
      return view('app.inventory.approving', compact('warehouse_code'));
   }
   public function approved($warehouse_code)
   {
      return view('app.inventory.approved',compact('warehouse_code'));
   }
   public function warehouses()
   {
      return view('app.inventory.warehouses');
   }

   public function approve($id){
      return view('app.inventory.approve_items', compact('id'));
   }

   public function handleApproval(Request $request)
   {
      //$selectedProducts = $request->input('selected_products', []);
      $selectedProducts = $request->input('selected_products');
      $allocateQuantities = $request->input('allocate', []);
      $user = $request->user();
      $warehouses='';
      $user_code = $user->user_code;
      $business_code = $user->business_code;
      $random = Str::random(20);
      if (empty($selectedProducts)) {
         Session()->flash('error','Not products selected');
         return Redirect::back();
      }else{
         foreach ($selectedProducts as $selectedProduct) {
            list($productId, $requisition_id) = explode('|', $selectedProduct);

            $requisitionProduct = RequisitionProduct::where('requisition_id', $requisition_id)
               ->where('product_id', $productId)
               ->first();

//            if (isset($allocateQuantities[$productId])) {
//               $allocatedQuantity = $allocateQuantities[$productId];
//               $requisitionProduct->allocated_quantity = $allocatedQuantity;
//               dd($requisitionProduct);
//               $requisitionProduct->save();
//            }
//            $allocatedQuantity = $allocateQuantities[$productId];

            $r=StockRequisition::where('id',$requisition_id)->first();
            $warehouses=$r->warehouse_code;

            if ($requisitionProduct) {
               if (isset($allocateQuantities[$productId])) {
                  $allocatedQuantity = min($allocateQuantities[$productId], $requisitionProduct->quantity);
                  $requisitionProduct->allocated_quantity = $allocatedQuantity;
                  $requisitionProduct->save();
               }

               if ($request->has('approve')) {
                  $requisitionProduct->update(['approval' => 1]);
                 StockRequisition::where('id',$requisition_id)->update([
                     'status'=>'Approved'
                  ]);
               } elseif ($request->has('disapprove')) {
                  $requisitionProduct->update(['approval' => 0]);
                  StockRequisition::where('id',$requisition_id)->update([
                     'status'=>'Disapproved'
                  ]);
               }
            }
         }
      }
      Session()->flash('success','Allocated products to sales person');
      return redirect('warehousing/all/requisitions/'.$warehouses);
//      return Redirect::back();
   }
}
