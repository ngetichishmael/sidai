<?php

namespace App\Http\Livewire\Creditors;

use App\Exports\customers as ExportsCustomers;
use App\Models\customer_group;
use App\Models\customers;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Approve extends Component
{
   use WithPagination;
   public $region = null;
   public $group = null;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public ?string $regional = null;

   public $user;

   public function __construct()
   {
      $this->user = Auth::user();
   }
    public function render()
    {
//        $searchTerm = '%' . $this->search . '%';
//        $contacts = customers::where('is_creditor', 1)->where('creditor_status', 'waiting_approval')
//            ->with('Area.Subregion.Region', 'Creator')
// //          ->search($searchTerm)
//           ->orderBy('id', 'DESC')
//           ->paginate($this->perPage);
       return view('livewire.creditors.approve', [
          'contacts' => $this->approvecustomers(),
          'regions' =>$this->region(),
          'groups' =>$this->groups()
       ]);
    }
    public function approvecustomers()
   {
      $aggregate = array();
      if ($this->user->account_type === "RSM" && empty($this->filter())) {
         return $aggregate;
      }
      $searchTerm = '%' . $this->search . '%';
      $regionTerm = '%' . $this->regional . '%';
      $aggregate = customers::select(
         'customers.customer_name as customer_name',
         'customers.phone_number as customer_number',
         'regions.name as region_name',
         'subregions.name as subregion_name',
         'areas.name as area_name',
         'customers.customer_type as customer_type',
         'customers.id as id',
         'customers.route_code as route',
         'customers.created_at as created_at'
      )
         ->join('areas', 'customers.route_code', '=', 'areas.id')
         ->leftJoin('subregions', 'areas.subregion_id', '=', 'subregions.id')
         ->leftJoin('regions', 'subregions.region_id', '=', 'regions.id')
         ->where('regions.name', 'like', $regionTerm)
         ->where(function ($query) use ($searchTerm) {
            $query->where('regions.name', 'like', $searchTerm)->orWhere('customer_name', 'like', $searchTerm)
               ->orWhere('phone_number', 'like', $searchTerm)->orWhere('address', 'like', $searchTerm);
         })
         ->where('creditor_status', 'waiting_approval');
      if ($this->user->account_type === "RSM") {
         $aggregate->whereIn('regions.id', $this->filter());
      }
      $aggregate = $aggregate->orderBy('customers.id', 'DESC')->paginate($this->perPage);

      return $aggregate;
   }
   public function filter(): array
   {

      $array = [];
      $user = Auth::user();
      $user_code = $user->region_id;
      if (!$user->account_type === 'RSM') {
         return $array;
      }
      $regions = Region::where('id', $user_code)->pluck('id');
      if ($regions->isEmpty()) {
         return $array;
      }
      $customers = customers::whereIn('region_id', $regions)->pluck('region_id');
      if ($customers->isEmpty()) {
         return $array;
      }
      return $customers->toArray();
   }
   public function creator($id)
   {
      $user_code = customers::whereId($id)->pluck('created_by')->implode('');
      $user = User::where('user_code', $user_code)->pluck('name')->implode('');
      return $user;
   }
    public function export()
      {
   return Excel::download(new ExportsCustomers, 'customers.xlsx');
      }
   public function deactivate($id)
   {
      customers::whereId($id)->update(
         ['approval' => "Suspended"]
      );
      return redirect()->to('/customer');
   }
   public function approveCreditor($id)
   {
      customers::whereId($id)->update(
         ['creditor_status' => "approved",
            'customer_type' => 'creditor',
            'is_creditor' => 1
         ]
      );
      return redirect()->to('/approveCreditors');
   }
   public function dissaproveCreditor($id)
   {
      customers::whereId($id)->update(
         ['creditor_status' => "disapproved",
            'customer_type' => 'creditor',
            'is_creditor' => 1
         ]
      );
      return redirect()->to('/approveCreditors');
   }
   public function activate($id)
   {
      customers::whereId($id)->update(
         ['approval' => "Approved"]
      );

      return redirect()->to('/customer');
   }

   public function region(){
      $region = Region::all();
      return $region;
   }
   public function groups(){
      $groups = customer_group::all();
      return $groups;
   }

}
