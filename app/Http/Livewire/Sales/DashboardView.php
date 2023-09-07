<?php

namespace App\Http\Livewire\Sales;

use App\Models\SalesTarget;
use Carbon\Carbon;
use Livewire\Component;

class DashboardView extends Component
{
   public $user_code;
   public $perPage = 10;
   public $search = '';
   public $timeFrame = 'quarter';
   public $start;
   public $end;

   public function render()
   {
      $today = Carbon::now();
      $targetsQuery = SalesTarget::with('User')->where('user_code', $this->user_code);

      // Apply time frame filter
      $this->applyTimeFrameFilter($targetsQuery);
      $this->Period($targetsQuery, $this->start, $this->end);
      // Fetch targets
      $targets = $targetsQuery->get();
      return view('livewire.sales.dashboard-view', [
         'targets' => $targets,
         'today' => $today
      ]);
   }
   public function Period($query, $start = null, $end = null)
   {
      if (is_null($start) && is_null($end)) {
         return $query;
      }
      if (!is_null($start) && Carbon::parse($start)->isSameDay(Carbon::parse($end))) {
         return $query->where('updated_at', '=', $start);
      }
      if (is_null($start)) {
         $start = Carbon::now()->startOfMonth()->format('Y-m-d');
      }
      if (is_null($end)) {
         $end = Carbon::now()->endOfMonth()->format('Y-m-d');
      }
      return $query->whereBetween('updated_at', [$start, $end]);
   }
   private function applyTimeFrameFilter($query)
   {
      $endDate = Carbon::now()->endOfMonth();

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
      $query->whereDate('Deadline', '<=', $endDate->format('Y-m-d'));
   }
   public function getSuccessRatio($achieved, $target)
   {
      if ($target != 0) {
         return number_format(($achieved / $target) * 100, 2);
      }

      return 0;
   }
}
