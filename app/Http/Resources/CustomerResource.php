<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
{
   /**
    * Transform the resource into an array.
    *  "latitude": "-1.396245",
   "longitude": "36.7409283",
   "latitude": "-1.396245",
   "longitude": "36.7409283",
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
   public function toArray($request)
   {
      return [
         'id' => $this->id,
         'name' => $this->customer_name,
         'user_code' => $this->id,
         'fcm_token' => "FCM Token",
         'address' => $this->address,
         'latitude' => $this->latitude,
         'longitude' => $this->longitude,
         'phone_number' => $this->phone_number,
         'number_visited' => $this->checkings_count,
         'orders' => OrderResource::collection($this->orders),
      ];
   }
}
