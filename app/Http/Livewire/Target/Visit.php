<?php

namespace App\Http\Livewire\Target;

use Carbon\Carbon;
use App\Models\User;
use Livewire\Component;
use App\Models\VisitsTarget;

class Visit extends Component
{
    public $perPage = 10;
    public $search = '';
    public $timeFrame = 'quarter';
    public function render()
    {

    //   $today = Carbon::now();
    //      $visits=VisitsTarget::all();
    //     return view('livewire.target.visit',[
    //      'visits'=>$visits,
    //      'today'=>$today
    //     ]);
    // }
    $targetsQuery = User::with(['TargetsVisit' => function ($query) {
       $query->orderBy('updated_at', 'asc');
    }])->where('account_type', '<>', 'Admin');
      $today = Carbon::now();
      // $targetsQuery = SalesTarget::query();
      // Apply search filter
      if (!empty($this->search)) {
         $targetsQuery->where('name', 'LIKE', '%' . $this->search . '%');
      }
      // Apply time frame filter
      $this->applyTimeFrameFilter($targetsQuery);
      // Fetch targets
      $targets = $targetsQuery->get();
      return view('livewire.target.visit', [
         'targets' => $targets,
         'today' => $today,
      ]);
   }

   private function applyTimeFrameFilter($query)
   {
      $endDate = Carbon::now();

      // Set end date based on selected time frame
      if ($this->timeFrame === 'quarter') {
         $endDate->endOfQuarter();
      } elseif ($this->timeFrame === 'half_year') {
         $endMonth = $endDate->month <= 6 ? 6 : 12;
         $endDate->setMonth($endMonth)->endOfMonth();
      } elseif ($this->timeFrame === 'year') {
         $endDate->endOfYear();
      }

      // Apply the filter
      $query->whereHas('TargetsVisit', function ($targetSaleQuery) use ($endDate) {
         $targetSaleQuery->whereDate('Deadline', '<=', $endDate->format('Y-m-d'));
      });
   }
   public function getSuccessRatio($achieved, $target)
   {
      if ($target != 0) {
         return number_format(($achieved / $target) * 100, 2);
      }

      return 0;
   }
}
