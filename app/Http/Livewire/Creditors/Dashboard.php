<?php

namespace App\Http\Livewire\Creditors;

use App\Models\Region;
use Livewire\Component;
use App\Models\customers;
use Livewire\WithPagination;
use App\Models\customer_group;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\customers as ExportsCustomers;

class Dashboard extends Component
{
    use WithPagination;
    public $group = null;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 25;
    public ?string $search = null;
    public ?string $regional = null;

    public $user;

    public function __construct()
    {
       $this->user = Auth::user();
    }
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $contacts = customers::where('is_creditor', 1)->where('creditor_status', 'approved')
         ->with('Area.Subregion.Region', 'Creator')
//         ->search($searchTerm)
//         ->where('customer_type', 'LIKE','creditor')
         ->orderBy('id', 'DESC')
         ->paginate($this->perPage);
         return view('livewire.creditors.dashboard', [
            'contacts' => $contacts,
            'regions' => $this->region(),
            'groups' => $this->groups()
         ]);
   }
   public function customers()
{
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
      ->where('customer_type', 'creditor');

   // Retrieve the filtered regions
   $filteredRegions = $this->filter();

   // If the filtered regions array is not empty, apply the filter to the query
   if (!empty($filteredRegions)) {
      $aggregate->whereIn('regions.id', $filteredRegions);
   } else {
      return []; // Empty array if no filtered regions
   }

   $aggregate->orderBy('customers.id', 'DESC');
   $paginateResults = $aggregate->paginate($this->perPage);

   return $paginateResults;
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
   public function updatedRegional()
   {
      // dd($this->regional);
      $this->search = null;
      $this->render();
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
         ['creditor_approved' => 1 ]
      );
      return redirect()->to('/creditors');
   }
   public function dissaproveCreditor($id)
   {
      customers::whereId($id)->update(
         ['creditor_approved' => 2 ]
      );
      return redirect()->to('/creditors');
   }
   public function activate($id)
   {
      customers::whereId($id)->update(
         ['approval' => "Approved"]
      );

      return redirect()->to('/customer');
   }
   public function region()
   {
      $region = Region::all();
      return $region;
   }
   public function groups()
   {
      $groups = customer_group::all();
      return $groups;
   }

}
