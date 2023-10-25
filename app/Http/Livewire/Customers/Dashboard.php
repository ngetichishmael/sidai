<?php

namespace App\Http\Livewire\Customers;

use App\Exports\CustomersExport;
use App\Models\customers;
use App\Models\customer_group;
use App\Models\price_group;
use App\Models\Region;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use PDF;
use Maatwebsite\Excel\Facades\Excel;

class Dashboard extends Component
{


   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   protected $pageName = 'page';
   public $perPage = 25;
    public $search = null;
    public $regional = null;
    public $orderBy = 'customers.id';
    public $orderAsc = false;
   public $group = null;
    public $user;
   public $start = null;
   public $end = null;
   public $selectedGroup = null;


    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
       return view('livewire.customers.dashboard', [
            'contacts' => $this->getPaginatedCustomers(),
            'regions' => $this->region(),
            'groups' => $this->groups(),
            'selectedGroup' => $this->selectedGroup,
        ]);
    }
    public function getPaginatedCustomers()
    {
       $aggregate = array();
        if ($this->user->account_type === "RSM" && empty($this->filter())) {
            return $aggregate;
        }
        $searchTerm = '%' . $this->search . '%';
        $regionTerm = '%' . $this->regional . '%';
        $aggregate = customers::join('areas', 'customers.route_code', '=', 'areas.id')
            ->leftJoin('subregions', 'areas.subregion_id', '=', 'subregions.id')
            ->leftJoin('regions', 'subregions.region_id', '=', 'regions.id')
            ->where('regions.name', 'like', $regionTerm)
            ->where(function ($query) use ($searchTerm) {
                $query->where('regions.name', 'like', $searchTerm)->orWhere('customer_name', 'like', $searchTerm)
                    ->orWhere('phone_number', 'like', $searchTerm)->orWhere('address', 'like', $searchTerm)
                   ->orWhereHas('Creator', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                   })
                   ->orWhereHas('Subregion', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                   })
                   ->orWhereHas('Area', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                   });
            })
            ->where('customer_type','like', 'normal')
            ->where('approval','LIKE','Approved');
        if ($this->user->account_type === "RSM") {
            $aggregate->whereIn('regions.id', $this->filter());
        }
       if ($this->selectedGroup) {
          $aggregate->where('customer_group', $this->selectedGroup);
       }
       $aggregate->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

       return $aggregate->paginate($this->perPage);
    }
    public function customers()
    {
       $aggregate = array();
        if ($this->user->account_type === "RSM" && empty($this->filter())) {
            return $aggregate;
        }
        $searchTerm = '%' . $this->search . '%';
        $regionTerm = '%' . $this->regional . '%';
        $aggregate = customers::join('areas', 'customers.route_code', '=', 'areas.id')
            ->leftJoin('subregions', 'areas.subregion_id', '=', 'subregions.id')
            ->leftJoin('regions', 'subregions.region_id', '=', 'regions.id')
            ->where('regions.name', 'like', $regionTerm)
            ->where(function ($query) use ($searchTerm) {
                $query->where('regions.name', 'like', $searchTerm)->orWhere('customer_name', 'like', $searchTerm)
                    ->orWhere('phone_number', 'like', $searchTerm)->orWhere('address', 'like', $searchTerm)
                   ->orWhereHas('Creator', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                   })
                   ->orWhereHas('Subregion', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                   })
                   ->orWhereHas('Area', function ($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm);
                   });
            })
            ->where('customer_type','like', 'normal')
            ->where('approval','LIKE','Approved');
        if ($this->user->account_type === "RSM") {
            $aggregate->whereIn('regions.id', $this->filter());
        }
       if ($this->selectedGroup) {
          $aggregate->where('customer_group', $this->selectedGroup);
       }
       $aggregate->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc');

       return $aggregate->get();
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
        $this->search = null;
        $this->render();
    }
    public function creator($id)
    {
        $user_code = customers::whereId($id)->pluck('created_by')->implode('');
        $user = User::where('user_code', $user_code)->pluck('name')->implode('');
        return $user;
    }
   public function export()
   {
      $filteredCustomers = $this->customers()->get();
      return Excel::download(new CustomersExport($filteredCustomers), 'customers.xlsx');
   }
   public function exportCSV()
   {
      $filteredCustomers = $this->customers()->get();
      return Excel::download(new CustomersExport($filteredCustomers), 'customers.csv');
   }

   public function exportPDF()
   {
      $data = [
         'contacts' => $this->customers()->get(),
      ];
      $pdf = PDF::loadView('Exports.customer_pdf', $data);

      // Add the following response headers
      return response()->streamDownload(function () use ($pdf) {
         echo $pdf->output();
      }, 'customers.pdf');
   }
    public function deactivate($id)
    {
        customers::whereId($id)->update(
            ['approval' => "Suspended"]
        );
        return redirect()->to('/customer');
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
        $groups = price_group::all();
        return $groups;
    }
}
