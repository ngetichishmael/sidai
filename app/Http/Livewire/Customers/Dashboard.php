<?php

namespace App\Http\Livewire\Customers;

use App\Exports\CustomersExport;
use App\Models\customers;
use App\Models\Orders;
use App\Models\price_group;
use App\Models\Region;
use App\Models\User;
use App\Models\warehouse_assign;
use App\Models\warehousing;
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
       $contacts=$this->getPaginatedCustomers();
        return view('livewire.customers.dashboard', [
            'contacts' => $contacts,
            'regions' => $this->region(),
            'groups' => $this->groups(),
            'selectedGroup' => $this->selectedGroup,
        ]);
    }
    public function getPaginatedCustomers()
    {

        $aggregate = array();
//        if ($this->user->account_type === "RSM" && empty($this->filter())) {
//            return $aggregate;
//        }
        $searchTerm = '%' . $this->search . '%';
        $regionTerm = '%' . $this->regional . '%';
        $aggregate = customers::with('Creator')
           ->join('areas', 'customers.route', '=', 'areas.id')
            ->LeftJoin('subregions', 'areas.subregion_id', '=', 'subregions.id')
            ->LeftJoin('regions', 'subregions.region_id', '=', 'regions.id')
//            ->where('regions.name', 'like', $regionTerm)
            ->where(function ($query) use ($searchTerm) {
                $query->where('regions.name', 'like', $searchTerm)
                   ->orWhere('customer_name', 'like', $searchTerm)
                    ->orWhere('customers.phone_number', 'like', $searchTerm)
//                   ->orWhere('address', 'like', $searchTerm)
                    ->orWhereHas('Creator', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('users.name', 'like', $searchTerm);
                    })
                    ->orWhereHas('Subregion', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', $searchTerm);
                    })
                   ->orWhereHas('Region', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', $searchTerm);
                    })
                    ->orWhereHas('Area', function ($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', $searchTerm);
                    });
            })
            ->where('customer_type', 'like', 'normal')
           ->where('approval', 'LIKE', ['Approved','approved']);
       if ($this->user->account_type === "RSM" || $this->user->account_type === "Shop-Attendee") {
            $aggregate->whereIn('customers.region_id', $this->filter());
        }
//       info($this->selectedGroup);
        if ($this->selectedGroup) {
            $aggregate->where('customer_group', $this->selectedGroup);
        }
        if ($this->startDate && $this->endDate) {
            $aggregate->whereBetween('customers.created_at', [$this->startDate, $this->endDate]);
        }

       $fstatus = 'Unknown';
       if ($this->selectedStatus === null || $this->selectedStatus ==='All' || empty($this->selectedStatus)) {
          // Define conditions for each status
          $statusConditions = [
             'New Inactive' => [
                ['customers.last_order_date', null],
                ['customers.created_at', '<', Carbon::now()->subDays(30)],
             ],
             'New' => [
                ['customers.last_order_date', null],
                ['customers.created_at', '>=', Carbon::now()->subDays(30)],
             ],
             'Inactive' => [
                ['customers.last_order_date', '!=', null],
                ['customers.last_order_date', '<', Carbon::now()->subDays(60)],
             ],
             'Partially Inactive' => [
                ['customers.last_order_date', '!=', null],
                ['customers.last_order_date', '>=', Carbon::now()->subDays(60)],
                ['customers.last_order_date', '<', Carbon::now()->subDays(30)],
             ],
             'Active' => [
                ['customers.last_order_date', '!=', null],
                ['customers.last_order_date', '>=', Carbon::now()->subDays(30)],
             ],
          ];
          // Check each status condition and set $fstatus
          $aggregate->where(function ($query) use ($statusConditions) {
             foreach ($statusConditions as $fstatus => $conditions) {
                $query->orWhere(function ($subQuery) use ($conditions) {
                   foreach ($conditions as $condition) {
                      $subQuery->where(...$condition);
                   }
                });
             }
          });
//          foreach ($statusConditions as $status => $conditions) {
//             if ($aggregate->where(function ($query) use ($conditions) {
//                   foreach ($conditions as $condition) {
//                      $query->where(...$condition);
//                   }
//                })->exists()) {
//                $fstatus = $status;
//                break;
//             }
//          }
       } else {
          //status filter
          if ($this->selectedStatus === 'active') {
             $fstatus = 'Active';
             $aggregate->whereNotNull('customers.last_order_date')->where('customers.last_order_date', '>=', Carbon::now()->subDays(30));
          } else if ($this->selectedStatus === 'partially_inactive') {
             $fstatus = 'Partially Inactive';
             $aggregate->whereNotNull('customers.last_order_date')
                ->whereBetween('customers.last_order_date', [
                   Carbon::now()->subDays(60)->format('Y-m-d'),
                   Carbon::now()->subDays(30)->format('Y-m-d')
                ]);
          } else if ($this->selectedStatus === 'inactive') {
             $fstatus = 'Inactive';
             $aggregate->whereNotNull('customers.last_order_date')->where('customers.last_order_date', '<', Carbon::now()->subDays(60));
          } else if ($this->selectedStatus === 'new') {
             $fstatus = 'New';
             $aggregate->whereNull('customers.last_order_date')
                ->whereBetween('customers.created_at', [Carbon::now()->subDays(30), Carbon::now()]);
          } else if ($this->selectedStatus === 'new_inactive') {
             $fstatus = 'New Inactive';
             $aggregate->whereNull('customers.last_order_date')
                ->where('customers.created_at', '<', Carbon::now()->subDays(30));
          }
//          dd("selected status  ",$aggregate->where('customer_name', 'laikipia pharmacy')->first());
       }
       $aggregate->select(
          'customers.id as id',
          'customers.customer_name',
          'customers.phone_number as customer_number',
          'regions.name as region_name',
          'subregions.name as subregion_name',
          'areas.name as area_name',
          'customers.created_by as user_code',
          'customers.updated_at',
          'customers.customer_group',
          'customers.created_at',
          'customers.last_order_date as last_order_date',
       );
       $results = $aggregate->orderBy('customers.updated_at', 'desc')->paginate($this->perPage);
       $results->getCollection()->transform(function ($result) use ($fstatus) {
          $result->fstatus = $fstatus;
          return $result;
       });
       return $results;

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
           ->select(
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
       $aggregate = customers::with('Creator')
          ->join('areas', 'customers.route', '=', 'areas.id')
          ->join('subregions', 'areas.subregion_id', '=', 'subregions.id')
          ->join('regions', 'subregions.region_id', '=', 'regions.id')
//          ->where('regions.name', 'like', $regionTerm)
          ->where(function ($query) use ($searchTerm) {
             $query->where('regions.name', 'like', $searchTerm)
                ->orWhere('customer_name', 'like', $searchTerm)
                ->orWhere('customers.phone_number', 'like', $searchTerm)
//                ->orWhere('address', 'like', $searchTerm)
                ->orWhereHas('Creator', function ($userQuery) use ($searchTerm) {
                   $userQuery->where('users.name', 'like', $searchTerm);
                })
                ->orWhereHas('Subregion', function ($userQuery) use ($searchTerm) {
                   $userQuery->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('Region', function ($userQuery) use ($searchTerm) {
                   $userQuery->where('name', 'like', $searchTerm);
                })
                ->orWhereHas('Area', function ($userQuery) use ($searchTerm) {
                   $userQuery->where('name', 'like', $searchTerm);
                });
          })
          ->where('customer_type', 'LIKE', 'normal')
          ->where('approval', 'LIKE', ['Approved','approved']);
       if ($this->user->account_type === "RSM" || $this->user->account_type === "Shop-Attendee") {
          dd($aggregate->get());
          $aggregate->whereIn('customers.regions_id', $this->filter());
       }
       if ($this->selectedGroup) {
          $aggregate->where('customer_group', $this->selectedGroup);
       }
       if ($this->startDate && $this->endDate) {
          $aggregate->whereBetween('customers.created_at', [$this->startDate, $this->endDate]);
       }
       $fstatus = 'Unknown';
       if ($this->selectedStatus === null || $this->selectedStatus ==='All' || empty($this->selectedStatus)) {
          // Define conditions for each status
          $statusConditions = [
             'New Inactive' => [
                ['customers.last_order_date', null],
                ['customers.created_at', '<', Carbon::now()->subDays(30)],
             ],
             'New' => [
                ['customers.last_order_date', null],
                ['customers.created_at', '>=', Carbon::now()->subDays(30)],
             ],
             'Inactive' => [
                ['customers.last_order_date', '!=', null],
                ['customers.last_order_date', '<', Carbon::now()->subDays(60)],
             ],
             'Partially Inactive' => [
                ['customers.last_order_date', '!=', null],
                ['customers.last_order_date', '>=', Carbon::now()->subDays(60)],
                ['customers.last_order_date', '<', Carbon::now()->subDays(30)],
             ],
             'Active' => [
                ['customers.last_order_date', '!=', null],
                ['customers.last_order_date', '>=', Carbon::now()->subDays(30)],
             ],
          ];
          // Check each status condition and set $fstatus
          foreach ($statusConditions as $status => $conditions) {
             if ($aggregate->where(function ($query) use ($conditions) {
                   foreach ($conditions as $condition) {
                      $query->where(...$condition);
                   }
                })->exists()) {
                $fstatus = $status;
                break;
             }
          }
       } else {
          //status filter
          if ($this->selectedStatus === 'active') {
             $fstatus = 'Active';
             $aggregate->whereNotNull('customers.last_order_date')->where('customers.last_order_date', '>=', Carbon::now()->subDays(30));
          } else if ($this->selectedStatus === 'partially_inactive') {
             $fstatus = 'Partially Inactive';
             $aggregate->whereNotNull('customers.last_order_date')
                ->whereBetween('customers.last_order_date', [
                   Carbon::now()->subDays(60)->format('Y-m-d'),
                   Carbon::now()->subDays(30)->format('Y-m-d')
                ]);
          } else if ($this->selectedStatus === 'inactive') {
             $fstatus = 'Inactive';
             $aggregate->whereNotNull('customers.last_order_date')->where('customers.last_order_date', '<', Carbon::now()->subDays(60));
          } else if ($this->selectedStatus === 'new') {
             $fstatus = ' New ';
             $aggregate->whereNull('customers.last_order_date')
                ->whereBetween('customers.created_at', [Carbon::now()->subDays(30), Carbon::now()]);
          } else if ($this->selectedStatus === 'new_inactive') {
             $fstatus = 'New Inactive';
             $aggregate->whereNull('customers.last_order_date')
                ->where('customers.created_at', '<', Carbon::now()->subDays(30));
          }
       }
       $aggregate->select(
          "*",
          'customers.id as id',
          'customers.customer_name',
          'customers.phone_number as customer_number',
          'regions.name as region_name',
          'subregions.name as subregion_name',
          'areas.name as area_name',
          'customers.created_by as user_code',
          'customers.address',
          'customers.updated_at',
          'customers.customer_group',
          'customers.price_group',
          'customers.created_at',
          'customers.last_order_date as last_order_date',
       );
       $results = $aggregate->orderBy('customers.updated_at', 'desc')->get();
       $results->transform(function ($result) use ($fstatus) {
          $result->fstatus = $fstatus;
          return $result;
       });
       return $results;
    }
    public function filter(): array
    {
       $array = [];
        $user = Auth::user();
        $user_code = $user->region_id;
        if (!$user->account_type === 'RSM' || !$user->account_type ==="Shop-Attendee") {
            return $array;
        }
       if ($user->account_type ==="Shop-Attendee"){
          $warehouse=warehouse_assign::where('manager', $user->user_code)->first();
          if (empty($warehouse)) {
             return $array;
          }
          $region=warehousing::where('warehouse_code', $warehouse->warehouse_code)->pluck('region_id');
          $customers = customers::whereIn('region_id', $region)->pluck('id');
       }else {
          $regions = Region::where('id', $user_code)->pluck('id');
          if (empty($regions)) {
             return $array;
          }
          $customers = customers::whereIn('region_id', $regions)->pluck('id');
       }
       if (empty($customers)) {
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
