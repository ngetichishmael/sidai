<?php

namespace App\Http\Resources;

use App\Models\customer\checkin;
use App\Models\customers;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\DB;

class TargetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
//    public function toArray($request)
//    {
//        $month = Carbon::now()->endOfMonth()->format('Y-m-d');
//        return [
//            'id' => $this->id,
//            'user_code' => $this->user_code,
//            'name' => $this->name,
//            'email' => $this->email,
//            'target_sale' => $this->TargetSale ? [
//                'SalesTarget' => $this->TargetSale->SalesTarget,
//                'AchievedSalesTarget' => $this->TargetSale->AchievedSalesTarget,
//                'Deadline' => $this->TargetSale->Deadline,
//            ] : [
//                'SalesTarget' => 0,
//                'AchievedSalesTarget' => 0,
//                'Deadline' => $month,
//            ],
//            'target_lead' => $this->TargetLead ? [
//                'LeadsTarget' => $this->TargetLead->LeadsTarget ?? 0,
//                'AchievedLeadsTarget' => $this->TargetLead->AchievedLeadsTarget ?? 0,
//                'Deadline' => $this->TargetLead->Deadline,
//            ] : [
//                'LeadsTarget' => 0,
//                'AchievedLeadsTarget' => 0,
//                'Deadline' => $month,
//            ],
//            'targets_order' => $this->TargetOrder ? [
//                'OrdersTarget' => $this->TargetOrder->OrdersTarget ?? 0,
//                'AchievedOrdersTarget' => $this->TargetOrder->AchievedOrdersTarget ?? 0,
//                'Deadline' => $this->TargetOrder->Deadline,
//            ] : [
//                'OrdersTarget' => 0,
//                'AchievedOrdersTarget' => 0,
//                'Deadline' => $month,
//            ],
//            'targets_visit' => $this->TargetVisit ? [
//                'VisitsTarget' => $this->TargetVisit->VisitsTarget ?? 0,
//                'AchievedVisitsTarget' => $this->TargetVisit->AchievedVisitsTarget ?? 0,
//                'Deadline' => $this->TargetVisit->Deadline,
//            ] : [
//                'VisitsTarget' => 0,
//                'AchievedVisitsTarget' => 0,
//                'Deadline' => $month,
//            ],
//        ];
//    }

   public function toArray($request)
   {
      $month = Carbon::now()->endOfMonth()->format('Y-m-d');
      return [
         'id' => $this->id,
         'user_code' => $this->user_code,
         'name' => $this->name,
         'email' => $this->email,
         'target_sale' => $this->TargetSale ? [
            'SalesTarget' => $this->TargetSale->SalesTarget,
            'AchievedSalesTarget' => $this->TargetSale->AchievedSalesTarget,
            'Deadline' => $this->TargetSale->Deadline,
            'achievement' => $this->getSalesAchieved($this->user_code),
         ] : [
            'SalesTarget' => 0,
            'AchievedSalesTarget' => 0,
            'Deadline' => $month,
            'achievement' => [
               'day' => 0,
               'week' => 0,
               'month' => 0,
            ],
         ],
         'target_lead' => $this->TargetLead ? [
            'LeadsTarget' => $this->TargetLead->LeadsTarget ?? 0,
            'AchievedLeadsTarget' => $this->TargetLead->AchievedLeadsTarget ?? 0,
            'Deadline' => $this->TargetLead->Deadline,
            'achievement' => $this->getLeadsAchieved($this->id),
         ] : [
            'LeadsTarget' => 0,
            'AchievedLeadsTarget' => 0,
            'Deadline' => $month,
            'achievement' => [
               'day' => 0,
               'week' => 0,
               'month' => 0,
            ],
         ],
         'targets_order' => $this->TargetsOrder ? [
            'OrdersTarget' => $this->TargetOrder->OrdersTarget ?? 0,
            'AchievedOrdersTarget' => $this->TargetOrder->AchievedOrdersTarget ?? 0,
            'Deadline' => $this->TargetOrder->Deadline,
            'achievement' => $this->getOrdersAchieved($this->user_code),
         ] : [
            'OrdersTarget' => 0,
            'AchievedOrdersTarget' => 0,
            'Deadline' => $month,
            'achievement' => [
               'day' => 0,
               'week' => 0,
               'month' => 0,
            ],
         ],

         'targets_visit' => $this->TargetVisit ? [
            'VisitsTarget' => $this->TargetVisit->VisitTarget ?? 0,
            'AchievedVisitsTarget' => $this->TargetVisit->AchievedVisitsTarget ?? 0,
            'Deadline' => $this->TargetVisit->Deadline,
            'achievement' => $this->getVisitAchieved($this->user_code),
         ] : [
            'VisitsTarget' => 0,
            'AchievedVisitsTarget' => 0,
            'Deadline' => $month,
            'achievement' => [
               'day' => 0,
               'week' => 0,
               'month' => 0,
            ],
         ],
      ];
   }

   public function getOrdersAchieved($user_code): array
   {
      $data = [
         'day' => 0,
         'week' => 0,
         'month' => 0,
      ];

      $currentDateTime = Carbon::now();
      $startOfDay = $currentDateTime->copy()->startOfDay();
      $endOfDay = $currentDateTime->copy()->endOfDay();
      $startOfWeek = $currentDateTime->copy()->startOfWeek();
      $endOfWeek = $currentDateTime->copy()->endOfWeek();
      $startOfMonth = $currentDateTime->copy()->startOfMonth();
      $endOfMonth = $currentDateTime->copy()->endOfMonth();

      $ordersToday = Orders::where('user_code', $user_code)->whereBetween('updated_at', [$startOfDay, $endOfDay])->pluck('order_code');
      $ordersThisWeek = Orders::where('user_code', $user_code)->whereBetween('updated_at', [$startOfWeek, $endOfWeek])->pluck('order_code');
      $ordersThisMonth = Orders::where('user_code', $user_code)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->pluck('order_code');

      $data['day'] = (int) Order_items::whereIn('order_code', $ordersToday->all())->sum('quantity');
      $data['week'] = (int) Order_items::whereIn('order_code', $ordersThisWeek->all())->sum('quantity');
      $data['month'] = (int) Order_items::whereIn('order_code', $ordersThisMonth->all())->sum('quantity');

      return $data;
   }
   public function getSalesAchieved($user_code): array
   {
      $data = [
         'day' => 0,
         'week' => 0,
         'month' => 0,
      ];

      $currentDateTime = Carbon::now();
      $startOfDay = $currentDateTime->copy()->startOfDay();
      $endOfDay = $currentDateTime->copy()->endOfDay();
      $startOfWeek = $currentDateTime->copy()->startOfWeek();
      $endOfWeek = $currentDateTime->copy()->endOfWeek();
      $startOfMonth = $currentDateTime->copy()->startOfMonth();
      $endOfMonth = $currentDateTime->copy()->endOfMonth();

      $ordersToday = Orders::where('order_type', 'Pre Order')->where('user_code', $user_code)->whereBetween('updated_at', [$startOfDay, $endOfDay])->pluck('order_code');
      $ordersThisWeek = Orders::where('order_type', 'Pre Order')->where('user_code', $user_code)->whereBetween('updated_at', [$startOfWeek, $endOfWeek])->pluck('order_code');
      $ordersThisMonth = Orders::where('order_type', 'Pre Order')->where('user_code', $user_code)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->pluck('order_code');

      $productIdsToday = Order_items::whereIn('order_code', $ordersToday->all())->pluck('productID');
      $productIdsThisWeek = Order_items::whereIn('order_code', $ordersThisWeek->all())->pluck('productID');
      $productIdsThisMonth = Order_items::whereIn('order_code', $ordersThisMonth->all())->pluck('productID');

      if ($productIdsToday->isEmpty() && $productIdsThisWeek->isEmpty() && $productIdsThisMonth->isEmpty()) {
         return $data;
      }

      $data['day'] = product_information::whereIn('id', $productIdsToday->all())
         ->select(DB::raw("CAST(SUM(CAST(SUBSTRING_INDEX(sku_code, '_', -1) AS UNSIGNED)) AS SIGNED) AS total_sum"))
         ->value('total_sum');

      $data['week'] = product_information::whereIn('id', $productIdsThisWeek->all())
         ->select(DB::raw("CAST(SUM(CAST(SUBSTRING_INDEX(sku_code, '_', -1) AS UNSIGNED)) AS SIGNED) AS total_sum"))
         ->value('total_sum');

      $data['month'] = product_information::whereIn('id', $productIdsThisMonth->all())
         ->select(DB::raw("CAST(SUM(CAST(SUBSTRING_INDEX(sku_code, '_', -1) AS UNSIGNED)) AS SIGNED) AS total_sum"))
         ->value('total_sum');

      return $data;
   }
   public function getLeadsAchieved($user_code): array
   {
      $data = [
         'day' => 0,
         'week' => 0,
         'month' => 0,
      ];

      $currentDateTime = Carbon::now();
      $startOfDay = $currentDateTime->copy()->startOfDay();
      $endOfDay = $currentDateTime->copy()->endOfDay();
      $startOfWeek = $currentDateTime->copy()->startOfWeek();
      $endOfWeek = $currentDateTime->copy()->endOfWeek();
      $startOfMonth = $currentDateTime->copy()->startOfMonth();
      $endOfMonth = $currentDateTime->copy()->endOfMonth();

      $data['day'] = customers::where('created_by', $user_code)->whereBetween('updated_at', [$startOfDay, $endOfDay])->count();
      $data['week'] = customers::where('created_by', $user_code)->whereBetween('updated_at', [$startOfWeek, $endOfWeek])->count();
      $data['month'] = customers::where('created_by', $user_code)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->count();

      return $data;
   }
   public function getVisitsAchieved($user_code): array
   {
      $data = [
         'day' => 0,
         'week' => 0,
         'month' => 0,
      ];

      $currentDateTime = Carbon::now();
      $startOfDay = $currentDateTime->copy()->startOfDay();
      $endOfDay = $currentDateTime->copy()->endOfDay();
      $startOfWeek = $currentDateTime->copy()->startOfWeek();
      $endOfWeek = $currentDateTime->copy()->endOfWeek();
      $startOfMonth = $currentDateTime->copy()->startOfMonth();
      $endOfMonth = $currentDateTime->copy()->endOfMonth();

      $data['day'] = checkin::where('user_code', $user_code)->whereBetween('updated_at', [$startOfDay, $endOfDay])->count();
      $data['week'] = checkin::where('user_code', $user_code)->whereBetween('updated_at', [$startOfWeek, $endOfWeek])->count();
      $data['month'] = checkin::where('user_code', $user_code)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->count();

      return $data;
   }

   private function getVisitAchieved($user_code): array
   {
      $data = [
         'day' => 0,
         'week' => 0,
         'month' => 0,
      ];

      $currentDateTime = Carbon::now();
      $startOfDay = $currentDateTime->copy()->startOfDay();
      $endOfDay = $currentDateTime->copy()->endOfDay();
      $startOfWeek = $currentDateTime->copy()->startOfWeek();
      $endOfWeek = $currentDateTime->copy()->endOfWeek();
      $startOfMonth = $currentDateTime->copy()->startOfMonth();
      $endOfMonth = $currentDateTime->copy()->endOfMonth();

      $data['day'] = checkin::where('user_code', $user_code)->whereBetween('updated_at', [$startOfDay, $endOfDay])->count();
      $data['week'] = checkin::where('user_code', $user_code)->whereBetween('updated_at', [$startOfWeek, $endOfWeek])->count();
      $data['month'] = checkin::where('user_code', $user_code)->whereBetween('updated_at', [$startOfMonth, $endOfMonth])->count();

      return $data;
   }
}
