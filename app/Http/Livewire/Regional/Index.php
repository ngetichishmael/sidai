<?php

namespace App\Http\Livewire\Regional;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Orders;
use App\Models\Region;
use Livewire\Component;
use App\Exports\RegionalExport;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
    public function render()
    {
        $regions = Region::all();
        $usercount = User::whereIn('account_type',['Customer'])->select('account_type', DB::raw('COUNT(*) as count'))
         ->groupBy('account_type')
         ->get();
        return view('livewire.regional.index', [
            'regions' => $regions,
            'orders' => $this->data(),
            'usercount' =>$usercount
        ]);
    }
    public function data()
   {
      $query = Orders::with('User', 'Customer')->where('order_type', 'Pre Order');
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

      return $query->paginate(10);
   }
    public function export()
   {
      return Excel::download(new RegionalExport, 'RegionalReport.xlsx');
   }
}
