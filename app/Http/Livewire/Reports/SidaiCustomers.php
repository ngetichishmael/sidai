<?php

namespace App\Http\Livewire\Reports;

use App\Exports\CustomersExport;
use App\Models\Area;
use App\Models\customers;
use App\Models\Subregion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class SidaiCustomers extends Component
{
    protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   use WithPagination;
   public $orderBy = 'id';
   public $orderAsc = true;
   public $user;
   public $search = null;

   public function __construct()
   {
      $this->user = Auth::user();
   }
    public function render()
    {
        return view('livewire.reports.sidai-customers',[
            'users' =>$this->data()
        ]);
    }
    public function data()
   {
      $query = customers::has('orders')->withCount('orders');
      $query->whereIn('id', $this->filter())->get();

      if (!empty($this->search)) {
         $query->where(function ($q) {
             $q->where('name', 'like', '%' . $this->search . '%');
         });
     }
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

      return $query->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
         ->paginate(25);
   }
   public function filter(): array
   {

      $array = [];
//      $user = $this->user;
      $user_code = $this->user->user_code;
      $dataAccessLevel = $this->user->roles()->pluck('data_access_level')->first();
      $subregions = Subregion::where('region_id', $this->user->region_id)->pluck('id');
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if (auth()->check() && $dataAccessLevel == 'route') {
         $customers = \App\Models\customer\customers::whereIn('route', $areas)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'subregional') {
         $customers = customers::whereIn('subregion_id', $subregions)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'regional') {
         $customers = customers::where('region_id', $this->user->region_id)->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }elseif (auth()->check() && $dataAccessLevel == 'all') {
         $customers = customers::all()->pluck('id');
         if ($customers->isEmpty()) {
            return $array;
         }
         return $customers->toArray();
      }
      else{
         return $array;
      }

   }
   public function export()
   {
      $customers = $this->data(); // Retrieve the data you want to export
      return Excel::download(new CustomersExport($customers), 'Customers.xlsx');
   }
}

