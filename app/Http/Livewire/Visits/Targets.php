<?php

namespace App\Http\Livewire\Visits;

use App\Models\User;
use App\Models\VisitsTarget;
use Carbon\Carbon;
use Livewire\Component;

class Targets extends Component
{

   public $Targets;
   public $users;
   public $countTargets = true;
   public $selectedAccountType;
   public function mount()
   {

      $today = Carbon::now();
      $lastDayofMonth =  Carbon::parse($today)->endOfMonth()->toDateString();
      $this->users  = User::whereNotIn('account_type', ['Customer','Admin'])->get();
      $this->loadUsers();
      $this->fill([
         'Targets' => collect([
            ['primarykey' => '', 'deadline' => $lastDayofMonth]
         ]),
      ]);
   }
   public function loadUsers()
   {
      if ($this->selectedAccountType && $this->selectedAccountType !== 'ALL') {
         $this->users = User::where('account_type', $this->selectedAccountType)->get();
      } else {
         $this->users = [];
      }
   }

   public function updatedSelectedAccountType()
   {
      $this->loadUsers();
   }
   public function addTargets()
   {
      $this->Targets->push(new VisitsTarget());
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

      $today = Carbon::now();
      $lastDayofMonth =    Carbon::parse($today)->endOfMonth()->toDateString();
      $this->validate([
         'Targets.*.primarykey' => 'required',
         'Targets.*.deadline' => 'required',
         'Targets.*.Target' => 'required',
      ]);
      foreach ($this->Targets as $value) {
         if ($value["primarykey"] === 'ALL') {
            if ($this->selectedAccountType && $this->selectedAccountType !== 'ALL') {
               $users = User::where('account_type', $this->selectedAccountType)->get();
            } else {
               $users = User::whereNotIn('account_type', ['Customer', 'Admin'])->get();
            }

            foreach ($users as $user) {
               VisitsTarget::updateOrCreate(
                  [
                     'user_code' => $user->user_code,
                     'Deadline' => $value['deadline'] ?? $lastDayofMonth
                  ],
                  [
                     'VisitsTarget' => $value['Target']
                  ]
               );
            }
         } else {
            VisitsTarget::updateOrCreate(
               [
                  'user_code' => $value["primarykey"],
               ],
               [
                  'Deadline' => $value['deadline'] ?? $lastDayofMonth,
                  'VisitsTarget' => $value['Target']
               ]
            );
         }
      }

      return redirect()->to('/target/visit');
   }
    public function render()
    {
      $account_types = User::whereNotIn('account_type', ['customer', 'Admin'])->groupBy('account_type')->get();
        return view('livewire.visits.targets',['account_types'=>$account_types]);
    }
}
