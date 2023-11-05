<?php

namespace App\Http\Livewire\Customers;

use App\Exports\CustomersExport;
use App\Models\customers;
use App\Models\Orders;
use App\Models\price_group;
use App\Models\Region;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

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
    public $startDate = null;
    public $endDate = null;
    public $selectedGroup = null;
   public $selectedStatus= ''; // 'all' is the default, other options might be 'active', 'partially_inactive', 'inactive', 'new', 'new_inactive'

   public function __construct()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
        return view('livewire.customers.dashboard', [
            'contacts' => $this->getPaginatedCustomers(),
//            'customers' => $this->getCustomers(),
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
        $aggregate = customers::with('Creator')->join('areas', 'customers.route_code', '=', 'areas.id')
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
            ->where('customer_type', 'like', 'normal')
            ->where('approval', 'LIKE', 'Approved');
        if ($this->user->account_type === "RSM" || $this->user->account_type === "Shop-Attendee") {
            $aggregate->whereIn('regions.id', $this->filter());
        }
        if ($this->selectedGroup) {
            $aggregate->where('customer_group', $this->selectedGroup);
        }
        if ($this->startDate && $this->endDate) {
            $aggregate->whereBetween('customers.created_at', [$this->startDate, $this->endDate]);
        }
       // Apply status filter
       if (!empty($this->selectedStatus)) {
          if ($this->selectedStatus === 'new') {
             // Filter customers where last order date is null
             $aggregate->where('last_order_date', '=',null)->where('customers.created_at', '>=', Carbon::now()->subDays(30));
          } elseif ($this->selectedStatus === 'active') {
             // Filter customers where last order date is within the last 30 days
             $aggregate->whereBetween('last_order_date', [Carbon::now()->subDays(30), Carbon::now()]);
          } elseif ($this->selectedStatus === 'partially_inactive') {
             // Filter customers where last order date is more than one month and less than or equal to three months
             $oneMonthAgo = Carbon::now()->subDays(30);
             $threeMonthsAgo = Carbon::now()->subDays(90);
             $aggregate->whereBetween('last_order_date', [$oneMonthAgo, $threeMonthsAgo]);
          } elseif ($this->selectedStatus === 'inactive') {
             // Filter customers where last order date is more than three months
             $threeMonthsAgo = Carbon::now()->subDays(90);
             $aggregate->where('last_order_date', '<', $threeMonthsAgo);
          } elseif ($this->selectedStatus === 'new_inactive') {
             $aggregate->where('last_order_date', '=',null)->where('customers.created_at', '<', Carbon::now()->subDays(30));
          }
       }

       $aggregate
//            ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
          ->orderBy('customers.updated_at', 'desc')->orderBy('customers.created_at', 'desc')
           ->select('*',
                'customers.id as id',
                'customers.customer_name',
                'customers.phone_number as customer_number',
                'regions.name as region_name',
                'subregions.name as subregion_name',
                'areas.name as area_name',
                'customers.created_by as user_code',
                'customers.updated_at',
                'customers.created_at',
                'customers.last_order_date as last_order_date',
            );
        return $aggregate->paginate($this->perPage);
    }
    public function getCreatorName($user_code)
    {
        return User::where('user_code', $user_code)->pluck('name')->implode('');
    }
    public function getLastOrderDate($id)
    {
        return Orders::where('customerID', $id)->latest('created_at')
           ->pluck('created_at')
           ->first();
    }
    public function getCustomers()
    {
        return customers::join('users', 'users.user_code', '=', 'customers.created_by')
            ->join('areas', 'customers.route', '=', 'areas.id')
            ->join('subregions', 'subregions.id', '=', 'areas.subregion_id')
            ->join('regions', 'regions.id', '=', 'subregions.region_id')
           ->orderBy('customers.updated_at', 'desc')->orderBy('customers.created_at', 'desc')
           ->select('*',
              'customers.id as id',
              'customers.customer_name',
              'customers.phone_number as customer_number',
              'regions.name as region_name',
              'subregions.name as subregion_name',
              'areas.name as area_name',
              'customers.created_by as user_code',
              'customers.updated_at',
              'customers.created_at',
           )
           ->get();
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
            ->where('customer_type', 'like', 'normal')
            ->where('approval', 'LIKE', 'Approved');
        if ($this->user->account_type === "RSM") {
            $aggregate->whereIn('regions.id', $this->filter());
        }
        if ($this->selectedGroup) {
            $aggregate->where('customer_group', $this->selectedGroup);
        }
        if ($this->startDate && $this->endDate) {
            $aggregate->whereBetween('customers.created_at', [$this->startDate, $this->endDate]);
        }
       // Apply status filter
       if (!empty($this->selectedStatus)) {
          if ($this->selectedStatus === 'new') {
             // Filter customers where last order date is null
             $aggregate->where('last_order_date', '=',null)->where('customers.created_at', '>=', Carbon::now()->subDays(30));
          } elseif ($this->selectedStatus === 'active') {
             // Filter customers where last order date is within the last 30 days
             $aggregate->whereBetween('last_order_date', [Carbon::now()->subDays(30), Carbon::now()]);
          } elseif ($this->selectedStatus === 'partially_inactive') {
             // Filter customers where last order date is more than one month and less than or equal to three months
             $oneMonthAgo = Carbon::now()->subDays(30);
             $threeMonthsAgo = Carbon::now()->subDays(90);
             $aggregate->whereBetween('last_order_date', [$oneMonthAgo, $threeMonthsAgo]);
          } elseif ($this->selectedStatus === 'inactive') {
             // Filter customers where last order date is more than three months
             $threeMonthsAgo = Carbon::now()->subDays(90);
             $aggregate->where('last_order_date', '<', $threeMonthsAgo);
          } elseif ($this->selectedStatus === 'new_inactive') {
             $aggregate->where('last_order_date', '=',null)->where('customers.created_at', '<', Carbon::now()->subDays(30));
          }
       }


       $aggregate->orderBy('customers.updated_at', 'desc')->orderBy('customers.created_at', 'desc')
           ->select('*',
              'customers.id as id',
              'customers.customer_name',
              'customers.phone_number as customer_number',
              'regions.name as region_name',
              'subregions.name as subregion_name',
              'areas.name as area_name',
              'customers.created_by as user_code',
              'customers.updated_at',
              'customers.created_at',
           );

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
        $filteredCustomers = $this->customers();
        return Excel::download(new CustomersExport($filteredCustomers), 'customers.xlsx');
    }
    public function exportCSV()
    {
        $filteredCustomers = $this->customers();
        return Excel::download(new CustomersExport($filteredCustomers), 'customers.csv');
    }

    public function exportPDF()
    {
        $data = [
            'contacts' => $this->customers(),
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
