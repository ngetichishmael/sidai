<?php

namespace App\Http\Livewire\Warehousing;

use Livewire\Component;

class AssignShopAttendee extends Component
{
   public $warehouse;
   public $shopattendee;

//   public function mount($warehouse)
//   {
//      $this->warehouse = $warehouse ? json_decode($warehouse, true) : [['user_code' => '']];
//   }
//
//
//   public function addTargets()
//   {
//      $this->warehouse[] = ['user_code' => ''];
//   }
//   public function removeTargets($index)
//   {
//      unset($this->warehouse[$index]);
//      $this->warehouse = array_values($this->warehouse);
//   }
//
//   public function submit()
//   {
//      $this->validate([
//         'warehouse.*.user_code' => 'required',
//      ]);
//
//      // Perform the necessary actions with the submitted data
//      foreach ($this->warehouse as $target) {
//         $warehouseCode = $target['warehouse_code'];
//         $userCode = $target['user_code'];
//      }
//      $this->warehouse = [
//         [
//            'warehouse_code' => $this->warehouseCode,
//            'user_code' => null,
//         ],
//      ];
//      session()->flash('success', 'Shop attendees assigned successfully.');
//   }

   public function mount($initialWarehouse)
   {
      $this->warehouse = $initialWarehouse ? json_decode($initialWarehouse, true) : [['user_code' => '']];
   }

   public function addTargets($userCode = '')
   {
      // Check if the user code already exists in the warehouse
      if (!$this->userCodeExists($userCode)) {
         $this->warehouse[] = ['user_code' => $userCode];
      } else {
         session()->flash('error', 'User is already assigned to a warehouse.');
      }
   }

   public function removeTargets($index)
   {
      unset($this->warehouse[$index]);
      $this->warehouse = array_values($this->warehouse);
   }

   public function submit()
   {
      $this->validate([
         'warehouse.*.user_code' => 'required',
      ]);

      session()->flash('success', 'Shop attendees assigned successfully.');
   }

   private function userCodeExists($userCode)
   {
      foreach ($this->warehouse as $target) {
         if ($target['user_code'] === $userCode) {
            return true;
         }
      }

      return false;
   }


   public function render()
   {
      return view('livewire.warehousing.assign-shop-attendee');
   }
}
