<?php

namespace App\Http\Livewire\Reports;

use App\Exports\VansaleExport;
use App\Models\Area;
use App\Models\Orders;
use App\Models\Subregion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Vansales extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      $count = 1;
      return view('livewire.reports.vansales', [
         'vansales' => $this->data(),
         'count' => $count
      ]);
   }
   public function data()
   {
      $query = Orders::with('User', 'Customer');
      if (!$this->user->account_type === 'RSM') {
         $query->whereIn('customerID', $this->filter());
      }


      $query->where('order_type', 'Van sales');
      if (!is_null($this->start)) {
         if (Carbon::parse($this->start)->equalTo(Carbon::parse($this->end))) {
            $query->whereDate('created_at', 'LIKE', "%" . $this->start . "%");
         } else {
            if (is_null($this->end)) {
               $this->end = Carbon::now()->endOfMonth()->format('Y-m-d');
            }
            $query->whereBetween('created_at', [$this->start, $this->end]);
         }
      }

      return $query->paginate(7);
   }
   public function filter(): array
   {

      $array = [];
      $user = $this->user;
      $user_code = $user->route_code;
      if (!$user->account_type === 'RSM') {
         return $array;
      }
      $subregions = Subregion::where('region_id', $user_code)->pluck('id');
      if ($subregions->isEmpty()) {
         return $array;
      }
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if ($areas->isEmpty()) {
         return $array;
      }
      $customers = customers::whereIn('route_code', $areas)->pluck('id');
      if ($customers->isEmpty()) {
         return $array;
      }
      return $customers->toArray();
   }
   public function export()
   {
      return Excel::download(new VansaleExport, 'vansales.xlsx');
   }
}
