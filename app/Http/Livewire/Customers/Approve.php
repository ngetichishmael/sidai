<?php

namespace App\Http\Livewire\Customers;
use App\Exports\customers as ExportsCustomers;
use App\Models\customer_group;
use App\Models\customers;
use App\Models\Region;
use App\Models\User;
use Illuminate\Support\Facades\Session;
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
        // dd($this->approvecustomers());

       return view('livewire.customers.approve', [
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
         ->where('customer_type', 'normal')
         ->where('approval', 'waiting_approval');
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
   public function approveCustomer($id)
   {
      customers::whereId($id)->update(
         ['approval' => "approved"]
      );
      Session::flash('success', 'Customer Approved!');
      return redirect()->to('/approveCustomers');
   }
   public function dissaproveCustomer($id)
   {
      customers::whereId($id)->update(
         ['approval' => "waiting_approval"]
      );
      return redirect()->to('/approveCustomers');
   }
   public function activate($id)
   {
      customers::whereId($id)->update(
         ['approval' => "approved"]
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
