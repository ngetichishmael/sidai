<?php

namespace App\Http\Controllers\app;

use App\Helpers\StockLiftHelper;
use App\Models\products\product_inventory;
use App\Models\RequisitionProduct;
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
   public function approval()
   {
      return view('app.inventory.approving');
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

      session()->flash('success','Allocate products to sales person');

      return redirect()->route('inventory.allocate.items',$code);
   }

   //allocate items
   public function allocate_items($code){
      return view('app.inventory.allocate_items', compact('code'));
   }
   public function approve($id){
      return view('app.inventory.approve_items', compact('id'));
   }

   public function handleApproval(Request $request)
   {
      $selectedProducts = $request->input('selected_products', []);
      $user = $request->user();
      $user_code = $user->user_code;
      $business_code = $user->business_code;
      $random = Str::random(20);
      $productIDs = array_column($selectedProducts, 'productID');
      $image_path = null;
      $stockedProducts = product_inventory::whereIn('productID', $productIDs)->get()->keyBy('productID');

      foreach ($selectedProducts as $productId) {
         $product = RequisitionProduct::find($productId);

         if ($product) {
            if ($request->has('approve')) {
               $product->update(['approval' => 1]);
                  $value= $productId;
                  $stocked = $stockedProducts->get($value['productID']);
                  (new StockLiftHelper())(
                     $user_code,
                     $business_code,
                     $value,
                     $image_path,
                     $random,
                     $stocked
                  );
            } elseif ($request->has('disapprove')) {
               $product->update(['approval' => 0]);
               product_inventory::whereId($productId)->increment('current_stock', $product->quantity);
            }
         }
      }
      session()->flash('success','Allocated products to sales person');
      return redirect('/warehousing/approve/'.$product->requisition_id);
   }

   public function handleApproval2(Request $request)
   {
      $selectedProducts = $request->input('selected_products', []);
      if (empty($selectedProducts)) {
         session()->flash('Error','Not products selected');
         return redirect('warehousing/all/stock-requisition');
      }else{
         foreach ($selectedProducts as $productId) {
            $product = RequisitionProduct::find($productId);

            if ($product) {
               if ($request->has('approve')) {
                  $product->update(['approval' => 1]);
                  product_inventory::whereId($productId)->decrement('current_stock', $product->quantity);
               } elseif ($request->has('disapprove')) {
                  $product->update(['approval' => 0]);
                  product_inventory::whereId($productId)->increment('current_stock', $product->quantity);
               }
            }
         }
      }
      session()->flash('success','Allocated products to sales person');
      return redirect('/warehousing/approve/'.$product->requisition_id);
   }


}
