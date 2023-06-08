<?php

namespace App\Http\Livewire\Orders;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\OrdersTarget;
use Illuminate\Support\Facades\Auth;

class Targets extends Component
{
   public $Targets;
   public $users;
   public $QPTargets;
   public $countTargets = true;
   public function mount()
   {

      $today = Carbon::now();
      $lastDayofMonth =  Carbon::parse($today)->endOfMonth()->toDateString();
      $this->users = User::where('account_type', ['TSR','TD'])->get();
      $this->QPTargets = OrdersTarget::get();
      $this->fill([
         'Targets' => collect([
            ['primarykey' => '', 'deadline' => $lastDayofMonth]
         ]),
      ]);
   }

   public function addTargets()
   {
      $this->Targets->push(new OrdersTarget());
      $this->countTargets = true;
   }

   public function removeTargets($index)
   {
      $this->Targets->pull($index);
      if (count($this->Targets) < 1) {
         $this->countTargets = false;
      }
   }
   public function submit()
   {
      $today = Carbon::now(); //Current Date and Time

      $lastDayofMonth =    Carbon::parse($today)->endOfMonth()->toDateString();
      $this->validate([
         'Targets.*.primarykey' => 'required',
         'Targets.*.deadline' => 'required',
         'Targets.*.Target' => 'required',
      ]);
      foreach ($this->Targets as $value) {
         if ($value["primarykey"] === 'ALL') {
            $users = User::where('account_type', ['TD','TSR'])->get();
            foreach ($users as $user) {
               OrdersTarget::updateOrCreate(
                  [
                     'user_code' => $user->user_code,
                     'Deadline' => $value['deadline'] ?? $lastDayofMonth,

                  ],
                  [
                     'OrdersTarget' => $value['Target'],
                  ]
               );
            }
         } else {
            OrdersTarget::updateOrCreate(
               [
                  'user_code' => $value["primarykey"],
                  'Deadline' => $value['deadline'] ?? $lastDayofMonth,
               ],
               [
                  'OrdersTarget' => $value['Target'],
               ]
            );
         }
      }
      return redirect()->to('/target/order');
   }
   public function render()
   {
      return view('livewire.orders.targets');
   }
}
