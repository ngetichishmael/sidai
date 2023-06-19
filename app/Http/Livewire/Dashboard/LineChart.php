<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Orders;
use App\Models\suppliers\suppliers;
use Livewire\Component;

class LineChart extends Component
{
   public function render()
   {
      return view('livewire.dashboard.line-chart', [

         'graphdata' => $this->getGraphData(),
      ]);
   }
   public function getGraphData()
   {
      $sidai = suppliers::where('name', 'LIKE', '%sidai%')->first();
      $months = [
         1 => 'January',
         2 => 'February',
         3 => 'March',
         4 => 'April',
         5 => 'May',
         6 => 'June',
         7 => 'July',
         8 => 'August',
         9 => 'September',
         10 => 'October',
         11 => 'November',
         12 => 'December',
      ];

      $preOrderCounts = Orders::where('order_type', 'Pre Order')
         ->whereIn('supplierID', [$sidai->id, '', null])
         ->where('order_status', 'DELIVERED')
         ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
         ->groupBy('month')
         ->pluck('count', 'month')
         ->toArray();

      $deliveryCounts = Orders::where('order_status', 'LIKE', '%deliver%')
         ->whereYear('created_at', '=', date('Y'))
         ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
         ->groupBy('month')
         ->pluck('count', 'month')
         ->toArray();

      $graphdata = [];
      for ($month = 1; $month <= 12; $month++) {
         $graphdata[] = [
            'month' => $months[$month],
            'preOrderCount' => $preOrderCounts[$month] ?? 0,
            'deliveryCount' => $deliveryCounts[$month] ?? 0,
         ];
      }

      return $graphdata;
   }
}
