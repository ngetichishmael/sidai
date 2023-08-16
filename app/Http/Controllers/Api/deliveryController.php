<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\customer\customers;
use App\Models\Delivery;
use App\Models\Delivery_items;
use App\Models\inventory\allocations;
use App\Models\inventory\items;
use App\Models\Orders;
use App\Models\products\product_inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @group Deliveries
 *
 * APIs to manage the product categories
 * */
class deliveryController extends Controller
{
   /**
    * User Delivery
    *
    * @param $businessCode
    * @param $userCode
    **/
   public function index($businessCode, $userCode)
   {
      $deliveries = Delivery::join('customers', 'customers.id', '=', 'delivery.customer')
         ->join('users', 'users.user_code', '=', 'delivery.allocated')
         ->where('delivery.business_code', $businessCode)
         ->where('delivery.allocated', $userCode)
         ->select('customer_name', 'name', 'delivery.created_at as delivery_date', 'delivery_status', 'order_code', 'delivery_code')
         ->orderBy('delivery.id', 'desc')
         ->get();

      return response()->json([
         "success" => true,
         "message" => "Delivery List",
         "data" => $deliveries
      ]);
   }

   /**
    * Delivery details
    *
    * @param $businessCode
    * @param $delivery_code
    **/
   public function details($delivery_code, $businessCode)
   {
      $delivery = Delivery::where('delivery_code', $delivery_code)->where('business_code', $businessCode)->first();
      $order = Orders::where('order_code', $delivery->order_code)->where('business_code', $businessCode)->first();
      $items = Delivery_items::join('product_information', 'product_information.id', '=', 'delivery_items.productID')
         ->where('delivery_code', $delivery_code)
         ->where('product_information.business_code', $businessCode)
         ->get();
      $customer = customers::where('id', $order->customerID)->first();


      return response()->json([
         "success" => true,
         "message" => "Delivery List",
         "delivery" => $delivery,
         "order"    => $order,
         "items"    => $items,
         "customer" => $customer,
      ]);
   }
   public function acceptDelivery(Request $request)
   {
    $user = $request->user();
    $user_code = $user->user_code;
    $business_code = $user->business_code;
    $random = Str::random(20);
    $data = $request->all();
    $codes = array();

    foreach ($data as $value) {
        $delivery = Delivery::where('delivery_code', $value["delivery_code"])->first();
        array_push($codes, $delivery);
        if ($delivery->Type === "Warehouse") {
            $delivery->update([
                'delivery_status' => 'pending',
                'Note' => $value["note"],
                'updated_by' => $request->user()->user_code,
            ]);
            $delivery_items = Delivery_items::where(
                'delivery_code',
                $value["delivery_code"]
            )->get();
            foreach ($delivery_items as $delivery_item) {
                items::updateOrCreate(
                    [
                        'product_code' => $delivery_item->productID,
                        'created_by' => $user_code
                    ],
                    [
                        'business_code' => $business_code,
                        'allocation_code' => $random,
                        'current_qty' => $delivery_item->allocated_quantity,
                        'allocated_qty' => $delivery_item->allocated_quantity,
                        'image' => $delivery_item->delivery_code,
                        'returned_qty' => 0,
                        'created_by' => $user_code,
                        'updated_by' => $user_code
                    ]
                );
                product_inventory::where('productID', $delivery_item->productID)
                    ->decrement('current_stock', $delivery_item->allocated_quantity);
                allocations::updateOrCreate(
                    [
                        "allocation_code" => $random,
                        "sales_person" => $user_code
                    ],
                    [
                        "business_code" => $business_code,
                        "status" => "pending",
                        "created_by" => $user_code,
                        "updated_by" => $user_code
                    ]
                );
            }
            return response()->json(
                [
                    "status" => 200,
                    "success" => true,
                    "message" => "Accepting deliveries..."
                ],
                200
            );
        } elseif ($delivery->Type === "Van_sale") {
            $delivery->update([
                'delivery_status' => 'pending',
                'Note' => $value["note"],
                'updated_by' => $request->user()->user_code,
            ]);
            $delivery_items = Delivery_items::where(
                'delivery_code',
                $value["delivery_code"]
            )->get();
            
            foreach ($delivery_items as $delivery_item) {
                items::updateOrCreate(
                    [
                        'product_code' => $delivery_item->productID,
                        'created_by' => $user_code
                    ],
                    [
                        'business_code' => $business_code,
                        'allocation_code' => $random,
                        'current_qty' => $delivery_item->allocated_quantity,
                        'allocated_qty' => $delivery_item->allocated_quantity,
                        'image' => $delivery_item->delivery_code,
                        'returned_qty' => 0,
                        'created_by' => $user_code,
                        'updated_by' => $user_code
                    ]
                );
                // product_inventory::where('productID', $delivery_item->productID)
                //     ->decrement('current_stock', $delivery_item->allocated_quantity);
                allocations::updateOrCreate(
                    [
                        "allocation_code" => $random,
                        "sales_person" => $user_code
                    ],
                    [
                        "business_code" => $business_code,
                        "status" => "pending",
                        "created_by" => $user_code,
                        "updated_by" => $user_code
                    ]
                );
            }
            
        }
    }

    return response()->json(
        [
            "status" => 200,
            "success" => true,
            "message" => "Accepting deliveries without stock..."
        ],
        200
    );
}
   public function rejectDelivery(Request $request)
   {
      $data = $request->all();
      foreach ($data as $value) {
         Delivery::where('delivery_code', $value["delivery_code"])->update([
            'delivery_status' => 'rejected',
            'Note' => $value["note"],
            'updated_by' => $request->user()->user_code,
         ]);
      }
      return response()->json(
         [
            "status" => 200,
            "success" => true,
            "message" => "Rejecting deliveries...",
         ],
         200
      );
   }
}
