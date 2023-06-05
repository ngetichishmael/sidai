<?php

namespace App\Http\Livewire\Individual;

use App\Charts\SalesTargetChart;
use App\Models\SalesTarget;
use Livewire\Component;

class Leads extends Component
{

   public $arraySLabel = [];
   public $arraySTargets = [];
   public $arraySAchieved = [];
   public $label;
   public function render()
   {
      $salestargetEx = SalesTarget::with('User')->get();
      foreach ($salestargetEx as $br) {
         array_push($this->arraySLabel, $br->User()->pluck('name')->implode(''));
         array_push($this->arraySTargets, $br->SalesTarget);
         array_push($this->arraySAchieved, $br->AchievedSalesTarget);
      }
      $this->label = "Sales Target";
      $salestarget = new SalesTargetChart();
      $salestarget->labels($this->arraySLabel);
      $salestarget->dataset($this->label, 'bar', $this->arraySTargets)->options([
         "responsive" => true,
         'color' => "#94DB9D",
         'backgroundColor' => "#009dde",
         "borderWidth" => 2,
         "borderRadius" => 5,
         "borderSkipped" => false,
      ]);
      $salestarget->labels($this->arraySLabel);
      $salestarget->dataset('Achieved', 'bar', $this->arraySAchieved)->options([
         "responsive" => true,
         'color' => [
            'rgba(255, 99, 132, 1)',
            'rgba(54, 162, 235, 1)',
            'rgba(255, 206, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
         ],
         'backgroundColor' => '#07ed41',
         "borderWidth" => 2,
         "borderRadius" => 5,
         "borderSkipped" => false,
      ]);
      return view('livewire.individual.leads', [
         'salestarget' => $salestarget,
      ]);
   }
}
