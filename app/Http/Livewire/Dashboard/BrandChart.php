<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\Orders;
use Livewire\Component;

class BrandChart extends Component
{
   public function render()
   {
//      $brands = DB::table('order_items')->select('product_name', DB::raw('SUM(total_amount) as total'))
//         ->groupBy('product_name')
//         ->orderBy('total', 'desc')
//         ->limit(7)
//         ->get();
//      $catergories = DB::table('order_items')->select('product_name', DB::raw('SUM(total_amount) as total'))
//         ->groupBy('product_name')
//         ->orderBy('total', 'asc')
//         ->limit(7)
//         ->get();
//      $arrayLabel = [];
//      $arrayData = [];
//      $arrayCLabel = [];
//      $arrayCData = [];
//      foreach ($brands as $br) {
//         array_push($arrayLabel, $br->product_name);
//         array_push($arrayData, $br->total);
//      }
//      foreach ($catergories as $br) {
//         array_push($arrayCLabel, $br->product_name);
//         array_push($arrayCData, $br->total);
//      }
//      $brandsales = new BrandSales();
//      $brandsales->labels($arrayLabel);
//      $brandsales->dataset('Best Performing Brand', 'bar', $arrayData)->options([
//         "responsive" => true,
//         'color' => "#94DB9D",
//         'backgroundColor' => '#009dde',
//         "borderWidth" => 2,
//         "borderRadius" => 5,
//         "borderSkipped" => true,
//      ]);
//      $brandsales->labels(array_reverse($arrayCLabel));
//      $brandsales->dataset('Least Performing Brand', 'bar', array_reverse($arrayCData))->options([
//         "responsive" => true,
//         'color' => "#94DB9D",
//         'backgroundColor' => '#f07f21',
//         "borderWidth" => 2,
//         "borderSkipped" => true,
//      ]);

      $startDate = now()->subMonth(); // Calculate start date (one month ago)
      $endDate = now(); // Current date

      $data = Orders::where('order_type', 'pre-orders')
         ->where('order_status', 'delivered')
         ->whereBetween('delivery_date', [$startDate, $endDate])
         ->select('delivery_date', 'qty')
         ->orderBy('delivery_date')
         ->get();
      $dates = $data->pluck('delivery_date')->map(function ($date) {
         return $date->format('Y-m-d'); // Format the date as needed
      })->toArray();

      $quantities = $data->pluck('qty')->toArray();
      return view('livewire.dashboard.brand-chart', [
         'dates' => $dates,
         'quantities'=>$quantities
      ]);
   }
}
