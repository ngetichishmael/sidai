<?php

namespace App\Http\Livewire\Reports;

use App\Exports\PaymentsExport;
use App\Models\Area;
use App\Models\customers;
use App\Models\order_payments;
use App\Models\Subregion;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Payments extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $start;
    public $end;
    use WithPagination;
    public $orderBy = 'id';
    public $orderAsc = true;
    public $user;

    public function __construct()
    {
        $this->user = Auth::user();
    }
    public function render()
    {
        return view('livewire.reports.payments', [
            'orders' => $this->data(),
        ]);
    }
    public function data()
    {
        $start = $this->start;
        $end = $this->end;

        return order_payments::select(
            'orders.id',
            'customers.customer_name',
            'orders.order_code',
            'customers.customer_type',
            'orders.created_at',
            DB::raw('COALESCE(SUM(order_payments.amount), 0) AS total_payment')
        )
            ->join('orders', 'orders.order_code', '=', 'order_payments.order_id')
            ->join('customers', 'customers.id', '=', 'orders.customerID')
            ->join('users', 'customers.created_by', '=', 'users.user_code')
            ->where('payment_method', 'PaymentMethods.Mpesa')
            ->when($start !== null, function ($query) use ($start, $end) {
                if (Carbon::parse($start)->equalTo(Carbon::parse($end))) {
                    $query->whereDate('orders.created_at', 'LIKE', "%" . $start . "%");
                } else {
                    if (is_null($end)) {
                        $end = Carbon::now()->endOfMonth()->format('Y-m-d');
                    }
                    $query->whereBetween('orders.created_at', [$start, $end]);
                }
            })
            ->groupBy('orders.id', 'customers.customer_name', 'orders.order_code', 'customers.customer_type', 'orders.created_at')
            ->orderBy('order_payments.id', 'desc')
            ->get();

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
        } elseif (auth()->check() && $dataAccessLevel == 'subregional') {
            $customers = customers::whereIn('subregion_id', $subregions)->pluck('id');
            if ($customers->isEmpty()) {
                return $array;
            }
            return $customers->toArray();
        } elseif (auth()->check() && $dataAccessLevel == 'regional') {
            $customers = customers::where('region_id', $this->user->region_id)->pluck('id');
            if ($customers->isEmpty()) {
                return $array;
            }
            return $customers->toArray();
        } elseif (auth()->check() && $dataAccessLevel == 'all') {
            $customers = customers::all()->pluck('id');
            if ($customers->isEmpty()) {
                return $array;
            }
            return $customers->toArray();
        } else {
            return $array;
        }
//      if (!$user->account_type === 'RSM') {
//         return $array;
//      }
    }
    public function filter2(): array
    {

        $array = [];
        $user = Auth::user();
        $user_code = $user->route_code;
        if (!$user->account_type === 'RSM') {
            return $array;
        }
        $subregions = Subregion::where('region_id', $user_code)->pluck('id');
        if ($subregions->isEmpty()) {
            return $array;
        }
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        if ($areas->isEmpty()) {
            return $array;
        }
        $customers = customers::whereIn('route_code', $areas)->pluck('id');
        if ($customers->isEmpty()) {
            return $array;
        }
        return $customers->toArray();
    }
    public function export()
    {
        return Excel::download(new PaymentsExport, 'Payments.xlsx');
    }
}
