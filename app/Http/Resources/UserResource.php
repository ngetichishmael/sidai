<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

   /**
    * Transform the resource into an array.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
    */
   public function toArray($request)
   {

      if ($request->routeIs('manager.orders')) {
         return [
            "Info" => "This shows the information about the " . $this->name,
            'id' => $this->id,
            'name' => $this->name,
            'user_code' => $this->user_code,
            'email' => $this->email,
            'location' => $this->location,
            'fcm_token' => $this->fcm_token,
            'number_visited' => $this->checkings_count,
            'orders' => OrderResource::collection($this->orders),
         ];
      } else if ('manager.users') {
         return [
            'id' => $this->id,
            'name' => $this->name,
            'user_code' => $this->user_code,
            'email' => $this->email,
            "role" => $this->account_type,
            "status" => $this->status,
            "customers" => $this->customers_count,
            'location' => $this->location,
            'fcm_token' => $this->fcm_token,
            'target_sales' => $this->sales($this->TargetSales),
            'target_leads' => $this->lead($this->Targetleads),
            'target_order' => $this->order($this->TargetsOrder),
            'target_visit' => $this->visit($this->TargetsVisit),
         ];
      } else {
         return [
            'id' => $this->id,
            'name' => $this->name,
            'user_code' => $this->user_code,
            'email' => $this->email,
            'location' => $this->location,
            'fcm_token' => $this->fcm_token,
         ];
      }
   }

   public function sales($value)
   {
      $array = [];

      foreach ($value as $keys) {
         $array["id"] = $keys["id"];
         $array["SalesTarget"] = $keys["SalesTarget"];
         $array["AchievedSalesTarget"] = $keys["AchievedSalesTarget"];
         $array["Deadline"] = $keys["Deadline"];
      }
      if (empty($array)) {
         $array["id"] = 0;
         $array["SalesTarget"] = "0";
         $array["AchievedSalesTarget"] = "0";
         $array["Deadline"] = Carbon::now()->format('Y-d-m');
      }
      return $array;
   }
   public function order($value)
   {
      $array = array();

      foreach ($value as $keys) {
//         $array["id"] = $keys["id"];
//         $array["OrderTarget"] = $keys["OrdersTarget"];
//         $array["AchievedOrderTarget"] = $keys["AchievedOrdersTarget"];
//         $array["Deadline"] = $keys["Deadline"];

         $item = [];
         $item["id"] = $keys["id"];
         $item["OrderTarget"] = $keys["OrdersTarget"];
         $item["AchievedOrderTarget"] = $keys["AchievedOrdersTarget"];
         $item["Deadline"] = $keys["Deadline"];
         $array[] = $item;
      }
      if (empty($array)) {
//         $array["id"] = 0;
//         $array["OrderTarget"] = "0";
//         $array["AchievedOrderTarget"] = "0";
//         $array["Deadline"] = Carbon::now()->format('Y-d-m');
         $item = [];
         $item["id"] = 0;
         $item["OrderTarget"] = "0";
         $item["AchievedOrderTarget"] = "0";
         $item["Deadline"] = Carbon::now()->format('Y-d-m');
         $array[] = $item;
      }
      return $array;
   }
   public function lead($value)
   {
      $array = array();

      foreach ($value as $keys) {
         $array["id"] = $keys["id"];
         $array["LeadTarget"] = $keys["LeadsTarget"];
         $array["AchievedLeadTarget"] = $keys["AchievedLeadsTarget"];
         $array["Deadline"] = $keys["Deadline"];
      }
      if (empty($array)) {
         $array["id"] = 0;
         $array["LeadTarget"] = "0";
         $array["AchievedLeadTarget"] = "0";
         $array["Deadline"] = Carbon::now()->format('Y-d-m');
      }
      return $array;
   }
   public function visit($value)
   {
      $array = array();

      foreach ($value as $keys) {
         $array["id"] = $keys["id"];
         $array["VisitTarget"] = $keys["VisitsTarget"];
         $array["AchievedVisitTarget"] = $keys["AchievedVisitsTarget"];
         $array["Deadline"] = $keys["Deadline"];
      }
      if (empty($array)) {
         $array["id"] = 0;
         $array["VisitTarget"] = "0";
         $array["AchievedVisitTarget"] = "0";
         $array["Deadline"] = Carbon::now()->format('Y-d-m');
      }
      return $array;
   }
}
