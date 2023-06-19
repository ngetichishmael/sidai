<?php

namespace App\Http\Livewire\Creditors;

use App\Exports\customers as ExportsCustomers;
use App\Models\customers;
use Livewire\Component;
use App\Models\Region;
use App\Models\customer_group;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{
    use WithPagination;
    public $group = null;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public ?string $search = null;
    public ?string $regional = null;
   public function render()
   {
      $searchTerm = '%' . $this->search . '%';
      $contacts = customers::with('Area.Subregion.Region', 'Creator')
         ->where('is_creditor', '=','1')
         ->where('creditor_approved', '=','1')
         ->search($searchTerm)
//         ->where('customer_type', 'LIKE','creditor')
         ->orderBy('id', 'DESC')
         ->paginate($this->perPage);
         return view('livewire.creditors.dashboard', [
            'contacts' => $this->customers(),
            'regions' => $this->region(),
            'groups' => $this->groups()
         ]);
   }
   public function customers()
   {
      $searchTerm = '%' . $this->search . '%';
      $regionTerm = '%' . $this->regional . '%';
      $aggregate = customers::join('areas', 'customers.route_code', '=', 'areas.id')
         ->leftJoin('subregions', 'areas.subregion_id', '=', 'subregions.id')
         ->leftJoin('regions', 'subregions.region_id', '=', 'regions.id')
         ->where('regions.name', 'like', $regionTerm)
         ->where(function ($query) use ($searchTerm) {
            $query->where('regions.name', 'like', $searchTerm)->orWhere('customer_name', 'like', $searchTerm)
               ->orWhere('phone_number', 'like', $searchTerm)->orWhere('address', 'like', $searchTerm);
         })
         ->where('customer_type', 'normal')
         ->get();

      return $aggregate;
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
