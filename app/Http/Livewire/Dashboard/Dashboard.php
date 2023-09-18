<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\warehouse_assign;
use App\Models\warehousing;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Orders;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Delivery;
use App\Models\customers;
use Livewire\WithPagination;
use App\Models\visitschedule;
use App\Models\customer\checkin;
use Illuminate\Support\Facades\DB;
use App\Models\suppliers\suppliers;
use Illuminate\Database\Eloquent\Builder;
use App\Models\order_payments as OrderPayment;

class Dashboard extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $startDate;
    public $endDate;
    public $daily;
    public $weekly;
    public $monthly;
    public $sumAll;
    public $perVansale = 10;
    public $perPreorder = 10;
    public $perBuyingCustomer = 10;
    public $perVisits = 10;
    public $perOrderFulfilment = 10;
    public $perActiveUsers = 10;
    public $perVisitTotal = 10;
    // Individual functions for data retrieval
    public function whereBetweenDate(Builder $query, string $column = null, string $start = null, string $end = null): Builder
    {
        if (is_null($start) && is_null($end)) {
            return $query;
        }

        if (!is_null($start) && Carbon::parse($start)->isSameDay(Carbon::parse($end))) {
            return $query->where($column, '=', $start);
        }
        $end = $end == null ? Carbon::now()->endOfMonth()->format('Y-m-d') : $end;
        return $query->whereBetween($column, [$start, $end]);
    }
    public function getCashAmount()
    {
       $loggedUser=Auth::user()->account_type;
       if (strcasecmp($loggedUser, "shop_attendee") === 0){
          $assignedwarehouse=warehouse_assign::where('manager', Auth::user()->user_code)->first();
          if (!empty($assignedwarehouse)){
             $warehouse=warehousing::where('warehouse_code', $assignedwarehouse->warehouse_code)->first();
             if (!empty($warehouse)) {
                $orderPayments = OrderPayment::where('payment_method', 'PaymentMethods.Cash')
                   ->whereHas('user', function ($query) use ($warehouse) {
                      $query->where('region_id', $warehouse->region_id);
                   })
                   ->whereBetween('updated_at', [$this->startDate, $this->endDate])
                   ->sum('amount');
                return $orderPayments;
             }
          }
       } elseif (strcasecmp($loggedUser, "rsm") === 0){
          $user=Auth::user();
          return OrderPayment::where('payment_method', 'PaymentMethods.Cash')
             ->whereHas('user', function ($query) use ($user ){
                $query->where('region_id', $user->region_id);
             })
             ->whereBetween('updated_at', [$this->startDate, $this->endDate])
             ->sum('amount');
       }else
        return OrderPayment::where('payment_method', 'PaymentMethods.Cash')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->sum('amount');
    }

    public function getMpesaAmount()
    {
       $loggedUser=Auth::user()->account_type;
       if (strcasecmp($loggedUser, "shop_attendee") === 0){
          $assignedwarehouse=warehouse_assign::where('manager', Auth::user()->user_code)->first();
          if (!empty($assignedwarehouse)){
             $warehouse=warehousing::where('warehouse_code', $assignedwarehouse->warehouse_code)->first();
             if (!empty($warehouse)) {
                return OrderPayment::where('payment_method', 'PaymentMethods.Mpesa')
                   ->whereHas('user', function ($query) use ($warehouse) {
                      $query->where('region_id', $warehouse->region_id);
                   })->whereBetween('updated_at', [$this->startDate, $this->endDate])->sum('amount');
             }}}elseif (strcasecmp($loggedUser, "rsm") === 0) {
          $user = Auth::user();
          return OrderPayment::where('payment_method', 'PaymentMethods.Mpesa')
             ->whereHas('user', function ($query) use ($user ){
                $query->where('region_id', $user->region_id);
             })->whereBetween('updated_at', [$this->startDate, $this->endDate])->sum('amount');
       }else
          return OrderPayment::where('payment_method', 'PaymentMethods.Mpesa')
             ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
             })->sum('amount');
    }

    public function getChequeAmount()
    {
        return OrderPayment::where('payment_method', 'PaymentMethods.Cheque')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->sum('amount');
    }

    public function getSalesAmount()
    {
        return OrderPayment::where(function (Builder $query) {
            $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
        })
            ->select('id', 'amount', 'balance', 'payment_method', 'isReconcile', 'user_id')
            ->sum('balance');
    }

    public function getTotalAmount()
    {
        return OrderPayment::where('payment_method', 'PaymentMethods.BankTransfer')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->sum('amount');
    }

    public function getVanSales()
    {

        return Orders::where('order_type', 'Van sales')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->whereIn('supplierID', [1, '', null])
            ->where('order_status', 'DELIVERED')
           ->orderBy('id', 'desc')
            ->sum('price_total');
    }

    public function getPreOrderCount()
    {
        $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

        return Orders::where('order_type', 'Pre Order')
            ->where(function ($query) use ($sidai) {
                $query->whereNull('supplierID')
                    ->orWhere('supplierID', '')
                    ->orWhere(function ($subquery) use ($sidai) {
                        if ($sidai !== null) {
                            $subquery->where('supplierID', 1);
                        }
                    });
            })
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->count();
    }
    public function getOrderFullmentByDistributorsCount()
    {
        $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
        return Orders::whereIn('order_status', ['Pending Delivery', 'Pending delivery'])
            ->where(function ($query) use ($sidai) {
                $query->whereNotNull('supplierID')
                    ->where('supplierID', '!=', '')
                    ->orWhere(function ($subquery) use ($sidai) {
                        if ($sidai !== null) {
                            $subquery->where('supplierID', 1);
                        }
                    });;
            })
            ->where('order_type', 'Pre Order')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->count();
    }
    public function getOrderFullmentByDistributorsPage()
    {

        return Orders::with('Customer', 'user', 'distributor')
            ->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])
            ->where(function ($query) {
                $query->whereNotNull('supplierID')
                    ->where('supplierID', '!=', '')
                    ->where('supplierID', '!=', 1);
            })
            ->where('order_type', 'Pre Order')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->paginate($this->perPreorder);
    }

    public function getOrderFullmentCount()
    {

        return Delivery::whereIn('delivery_status', ['Delivered', 'DELIVERED', 'Partial Delivery'])
            ->where(function ($query) {
                $query->whereHas('Order', function ($subQuery) {
                    $subQuery->whereNull('supplierID')
                        ->where('supplierID', '=', '')
                        ->where('supplierID', '=', 1);
                })->whereHas('Order', function ($subQuery) {
                    $subQuery->where('order_type', 'Pre Order');
                });
            })
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->count();
    }

    public function getActiveUserCount()
    {
        return checkin::where(function (Builder $query) {
            $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
        })
            ->distinct('user_code')
            ->count();
    }

    public function getActiveAllCount()
    {
        return User::where('account_type', '!=', 'Customer')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->count();
    }

    public function getStrikeCount()
    {
        return checkin::where(function (Builder $query) {
            $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
        })
            ->count();
    }

    public function getCustomersCount()
    {
        return customers::where(function (Builder $query) {
            $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
        })
            ->count();
    }

    public function getVanSalesTotal()
    {
        return Orders::with('User', 'Customer')
            ->where('order_type', 'Van sales')
            ->where(function ($query) {
                $query->whereNull('supplierID')
                    ->orWhere('supplierID', '')
                    ->orWhere('supplierID', 1);
            })
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->where('order_status', 'DELIVERED')->orderBy('id', 'desc')
            ->paginate($this->perVansale);
    }

    public function getPreOrderTotal()
    {
        return Orders::with('User', 'Customer')
            ->where('order_type', 'Pre Order')
            ->where(function ($query) {
                $query->whereNull('supplierID')
                    ->orWhere('supplierID', '')
                    ->orWhere('supplierID', 1);
            })->where(function (Builder $query) {
            $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
        })->orderBy('id', 'desc')
            ->paginate($this->perPreorder);
    }

    public function getActiveUserTotal()
    {
        return checkin::with('User', 'Customer')
            ->distinct('user_code')
            ->groupBy('user_code')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->paginate($this->perActiveUsers);
    }
    public function getTotalVisits()
    {
        return visitschedule::whereNotNull('shopID')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->paginate($this->perVisitTotal);
    }
    public function getOrderFullmentTotal()
    {
        return Delivery::whereIn('delivery_status', ['Delivered', 'DELIVERED', 'Partial Delivery'])
            ->where(function ($query) {
                $query->whereHas('Order', function ($subQuery) {
                    $subQuery->whereNull('supplierID')
                        ->where('supplierID', '=', '')
                        ->where('supplierID', '=', 1);
                })->whereHas('Order', function ($subQuery) {
                    $subQuery->where('order_type', 'Pre Order');
                });
            })
            ->with('User', 'Customer')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->paginate($this->perOrderFulfilment);
    }

    public function getVisitsTotal()
    {
        return checkin::with('User', 'Customer')
            ->groupBy('customer_id')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->paginate($this->perVisits);
    }

    public function getCustomersCountTotal()
    {
        return customers::with('Area', 'Creator', 'Region')
            ->where(function (Builder $query) {
                $this->whereBetweenDate($query, 'updated_at', $this->startDate, $this->endDate);
            })
            ->paginate($this->perBuyingCustomer);
    }

    public function getGraphData()
    {
        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];
        $preOrderCounts = Orders::whereYear('updated_at', '=', date('Y'))
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();
        $deliveryCounts = Delivery::whereIn('delivery_status', ['Delivered', 'DELIVERED', 'Partial Delivery'])
            ->where(function ($query) {
                $query->whereHas('Order', function ($subQuery) {
                    $subQuery->whereNull('supplierID')
                        ->where('supplierID', '=', '')
                        ->where('supplierID', '=', 1);
                })->whereHas('Order', function ($subQuery) {
                    $subQuery->where('order_type', 'Pre Order');
                });
            })
            ->whereYear('updated_at', '=', date('Y'))
            ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $graphdata = [];
        for ($month = 1; $month <= 12; $month++) {
            $graphdata[] = [
                'month' => $months[$month],
                'preOrderCount' => $preOrderCounts[$month] ?? $month++,
                'deliveryCount' => $deliveryCounts[$month] ?? $month++,
            ];
        }
        return $graphdata;
    }
    public function render()
    {
        $data = [
            'Cash' => $this->getCashAmount(),
            'Mpesa' => $this->getMpesaAmount(),
            'Cheque' => $this->getChequeAmount(),
            'sales' => $this->getSalesAmount(),
            'total' => $this->getTotalAmount(),
            'vansales' => $this->getVanSales(),
            'preorder' => $this->getPreOrderCount(),
            'orderfullmentbydistributors' => $this->getOrderFullmentByDistributorsCount(),
            'orderfullmentbydistributorspage' => $this->getOrderFullmentByDistributorsPage(),
            'orderfullment' => $this->getOrderFullmentCount(),
            'activeUser' => $this->getActiveUserCount(),
            'activeAll' => $this->getActiveAllCount(),
            'strike' => $this->getStrikeCount(),
            'customersCount' => $this->getCustomersCount(),
            'vansalesTotal' => $this->getVanSalesTotal(),
            'preorderTotal' => $this->getPreOrderTotal(),
            'activeUserTotal' => $this->getActiveUserTotal(),
            'getTotalVisits' => $this->getTotalVisits(),
            'orderfullmentTotal' => $this->getOrderFullmentTotal(),
            'visitsTotal' => $this->getVisitsTotal(),
            'customersCountTotal' => $this->getCustomersCountTotal(),
            'graphdata' => $this->getGraphData(),

        ];

        return view('livewire.dashboard.dashboard', $data);
    }

    public function mount()
    {
        $today = Carbon::today();
        $week = Carbon::now()->subWeeks(1);

        $this->daily = DB::table('order_payments')
            ->whereDate('created_at', $today)
            ->sum('amount');
        $this->weekly = DB::table('order_payments')
            ->whereBetween('created_at', [$week, $today])
            ->sum('amount');
        $this->monthly = DB::table('order_payments')
            ->whereBetween('created_at', [$week, $today])
            ->sum('amount');
        $this->sumAll = DB::table('order_payments')
            ->sum('amount');
    }
    public function updatedStart()
    {
        $this->changes();
    }
    public function updatedEnd()
    {
        $this->changes();
    }
    public function changes()
    {
        $this->mount();
        $this->render();
        $this->getCashAmount();
        $this->getMpesaAmount();
        $this->getChequeAmount();
        $this->getSalesAmount();
        $this->getTotalAmount();
        $this->getVanSales();
        $this->getPreOrderCount();
        $this->getOrderFullmentByDistributorsCount();
        $this->getOrderFullmentByDistributorsPage();
        $this->getOrderFullmentCount();
        $this->getActiveUserCount();
        $this->getActiveAllCount();
        $this->getStrikeCount();
        $this->getCustomersCount();
        $this->getVanSalesTotal();
        $this->getPreOrderTotal();
        $this->getActiveUserTotal();
        $this->getTotalVisits();
        $this->getOrderFullmentTotal();
        $this->getVisitsTotal();
        $this->getCustomersCountTotal();
        $this->getGraphData();
    }
}
