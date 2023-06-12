<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
   /**
    * Transform the resource into an array.
    *"id": 24,
   "order_code": "6ygoWsVB",
   "productID": 1,
   "product_name": "Heineken",
   "quantity": 13,
   "sub_total": "0",
   "total_amount": "0",
   "selling_price": "0",
   "discount": "0",
   "taxrate": 0,
   "taxvalue": "0",
   "created_at": "2022-11-15T18:36:37.000000Z",
   "updated_at": "2022-11-15T18:36:37.000000Z"
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
   public function toArray($request)
   {
      return [
         "Info" => "This shows  order item for " . $this->order_code,
         'id' => $this->id,
         'productID' => $this->productID,
         'product_name' => $this->product_name,
         'quantity' => $this->quantity,
         'total' => $this->total_amount
      ];
   }
}
