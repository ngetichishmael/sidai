<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class TargetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
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
            ] : [
                'SalesTarget' => 0,
                'AchievedSalesTarget' => 0,
                'Deadline' => $month,
            ],
            'target_lead' => $this->TargetLead ? [
                'LeadsTarget' => $this->TargetLead->LeadsTarget ?? 0,
                'AchievedLeadsTarget' => $this->TargetLead->AchievedLeadsTarget ?? 0,
                'Deadline' => $this->TargetLead->Deadline,
            ] : [
                'LeadsTarget' => 0,
                'AchievedLeadsTarget' => 0,
                'Deadline' => $month,
            ],
            'targets_order' => $this->TargetOrder ? [
                'OrdersTarget' => $this->TargetOrder->OrdersTarget ?? 0,
                'AchievedOrdersTarget' => $this->TargetOrder->AchievedOrdersTarget ?? 0,
                'Deadline' => $this->TargetOrder->Deadline,
            ] : [
                'OrdersTarget' => 0,
                'AchievedOrdersTarget' => 0,
                'Deadline' => $month,
            ],
            'targets_visit' => $this->TargetVisit ? [
                'VisitsTarget' => $this->TargetVisit->VisitsTarget ?? 0,
                'AchievedVisitsTarget' => $this->TargetVisit->AchievedVisitsTarget ?? 0,
                'Deadline' => $this->TargetVisit->Deadline,
            ] : [
                'VisitsTarget' => 0,
                'AchievedVisitsTarget' => 0,
                'Deadline' => $month,
            ],
        ];
    }
}
