<?php

namespace App\Http\Livewire\Dashboard;

use Livewire\Component;

class IndividualTargets extends Component
{
   public $label;
   public $type;

   public function render()
   {

      return view('livewire.dashboard.individual-targets');
   }
   public function mount()
   {
      $this->type = "sale";
   }
   public function updatedType()
   {
      $this->render();
   }
}
