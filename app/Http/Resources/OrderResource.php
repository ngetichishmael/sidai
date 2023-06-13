<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{





   /**
    * Transform the resource into an array.

    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */


   public function toArray($request)
   {
      if ($request->routeIs('manager.transaction')) {

         return [
            'id' => $this->id,
            'name' => $this->customerName($this->Customer),
            'status' => $this->payment_status,
            'order_id' => $this->order_code,
            'total_amount' => $this->price_total,
            'balance' => $this->price_total,
            'order_status' => $this->order_status,
            'paymentTime' => $this->updated_at,
         ];
      } else {

         return [
            "Info" => "This shows the information about the order " . $this->order_code,
            'id' => $this->id,
            'order_code' => $this->order_code,
            'payment_status' => $this->payment_status,
            'price_total' => $this->price_total,
            'balance' => $this->price_total,
            'order_status' => $this->order_status,
            'order_items' => OrderItemResource::collection($this->OrderItem),
         ];
      }
   }
   public function customerName($data)
   {
      $name = "Error";
      if ($data !== null) {
         $name = $data["customer_name"];
      }

      return $name;
   }
}
