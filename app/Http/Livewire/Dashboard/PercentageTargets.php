<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\SalesTarget;
use Livewire\Component;

class PercentageTargets extends Component
{

   public $salestarget;
   public $Targets;
   public $TargetAchieved;
   public function render()
   {

      return view('livewire.dashboard.percentage-targets');
   }
   public function mount()
   {
      $this->salestarget = ['Total Sales Target', 'Total Sales Achieved'];
      $this->Targets = SalesTarget::sum('SalesTarget');
      $this->TargetAchieved = SalesTarget::sum('AchievedSalesTarget');
   }
}
