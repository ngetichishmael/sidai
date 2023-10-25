<?php

namespace App\Http\Livewire\Target;

use App\Exports\TargetExport;
use App\Models\Area;
use App\Models\Subregion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   public $user;
   use WithPagination;
   public $orderBy = 'users.id';
   public $orderAsc = true;
   public function __construct()
   {
      $this->user = Auth::user();
   }
   public function render()
   {
      return view('livewire.target.index', [
         'targets' => $this->data()
      ]);
   }
   public function data()
   {
      $dataAccessLevel = $this->user->roles()->pluck('data_access_level')->first();
      $subregions = Subregion::where('region_id', $this->user->region_id)->pluck('id');
      $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
      if (auth()->check() && $dataAccessLevel == 'all') {
         $result = DB::table('users AS u');
      }else{
         $result = DB::table('users AS u')->where('region_id', $this->user->region_id);
      }
      $result->select(
            'u.name AS user_name',
            'u.account_type AS user_type',
            'lt.LeadsTarget AS leads_target',
            'lt.AchievedLeadsTarget AS leads_achieved',
            'ot.OrdersTarget AS orders_target',
            'ot.AchievedOrdersTarget AS orders_achieved',
            'st.SalesTarget AS sales_target',
            'st.AchievedSalesTarget AS sales_achieved',
            'vt.VisitsTarget AS visits_target',
            'vt.AchievedVisitsTarget AS visits_achieved'
         )
         ->leftJoin('leads_targets AS lt', 'u.user_code', '=', 'lt.user_code')
         ->leftJoin('orders_targets AS ot', 'u.user_code', '=', 'ot.user_code')
         ->leftJoin('sales_targets AS st', 'u.user_code', '=', 'st.user_code')
         ->leftJoin('visits_targets AS vt', 'u.user_code', '=', 'vt.user_code')
         ->get();
      return $result->paginate(25);
   }
   public function export()
   {
      return Excel::download(new TargetExport, 'Targets.xlsx');
   }
}
