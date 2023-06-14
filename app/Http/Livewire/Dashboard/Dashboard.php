<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\customer\checkin;
use App\Models\customers;
use App\Models\Delivery;
use App\Models\Orders;
use App\Models\suppliers\suppliers;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

use App\Models\order_payments as OrderPayment;
use App\Models\User;
use Livewire\WithPagination;

class Dashboard extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $start;
   public $end;
   public  $daily;
   public  $weekly;
   public  $monthly;
   public  $sumAll;
   public $perVansale = 10;
   public $perPreorder = 10;
   public $perBuyingCustomer = 10;
   public $perVisits = 10;
   public $perOrderFulfilment = 10;
   public $perActiveUsers = 10;

   // Individual functions for data retrieval


   public function getCashAmount()
   {
      return OrderPayment::where('payment_method', 'PaymentMethods.Cash')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->sum('amount');
   }

   public function getMpesaAmount()
   {
      return OrderPayment::where('payment_method', 'PaymentMethods.Mpesa')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->sum('amount');
   }

   public function getChequeAmount()
   {
      return OrderPayment::where('payment_method', 'PaymentMethods.Cheque')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->sum('amount');
   }

   public function getSalesAmount()
   {
      return DB::table('order_payments')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->select('id', 'amount', 'balance', 'payment_method', 'isReconcile', 'user_id')
         ->sum('balance');
   }

   public function getTotalAmount()
   {
      return $this->getCashAmount() + $this->getChequeAmount() + $this->getMpesaAmount();
   }

   public function getVanSales()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

      return Orders::where('order_type', 'Van sales')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->whereIn('supplierID', [$sidai->id, '', null])
         ->where('order_status', 'DELIVERED')
         ->sum('price_total');
   }

   public function getPreOrderCount()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

      return Orders::where('order_type', 'Pre Order')
         ->where(function ($query) use ($sidai) {
            $query->whereNull('supplierID')
               ->orWhere('supplierID', '')
               ->orWhere('supplierID', $sidai->id);
         })
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->count();
   }
   public function getOrderFullmentByDistributorsCount()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
      return Orders::whereIn('order_status', ['Pending Delivery', 'Pending delivery'])
         ->where(function ($query) use ($sidai) {
            $query->whereNotNull('supplierID')
               ->where('supplierID', '!=', '')
               ->where('supplierID', '!=', $sidai->id);
         })
         ->where('order_type', 'Pre Order')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->count();
   }
   public function getOrderFullmentByDistributorsPage()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

      return Orders::with('Customer', 'user', 'distributor')
         ->whereIn('order_status', ['Pending Delivery', 'Pending delivery'])
         ->where(function ($query) use ($sidai) {
            $query->whereNotNull('supplierID')
               ->where('supplierID', '!=', '')
               ->where('supplierID', '!=', $sidai->id);
         })
         ->where('order_type', 'Pre Order')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->paginate($this->perPreorder);
   }

   public function getOrderFullmentCount()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

      return Delivery::whereIn('delivery_status', ['Delivered', 'DELIVERED', 'Partial Delivery'])
         ->where(function ($query) use ($sidai) {
            $query->whereHas('Order', function ($subQuery) use ($sidai) {
               $subQuery->whereNull('supplierID')
                  ->where('supplierID', '=', '')
                  ->where('supplierID', '=', $sidai->id);
            })->whereHas('Order', function ($subQuery) {
               $subQuery->where('order_type', 'Pre Order');
            });
         })
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->count();
   }

   public function getActiveUserCount()
   {
      return DB::table('customer_checkin')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->distinct('user_code')
         ->count();
   }

   public function getActiveAllCount()
   {
      return User::where('status', 'Active')
         ->where('account_type', '!=', 'Customer')
         ->count();
   }

   public function getStrikeCount()
   {
      return DB::table('customer_checkin')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->count();
   }

   public function getCustomersCount()
   {
//      return Orders::groupBy('customerID')
//         ->whereBetween('created_at', [$this->start, $this->end])
//         ->count();
      return customers::whereBetween('created_at', [$this->start, $this->end])
         ->count();
   }

   public function getVanSalesTotal()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

      return Orders::with('User', 'Customer')
         ->where('order_type', 'Van sales')
         ->where(function ($query) use ($sidai) {
            $query->whereNull('supplierID')
               ->orWhere('supplierID', '')
               ->orWhere('supplierID', $sidai->id);
         })
         ->whereBetween('created_at', [$this->start, $this->end])
         ->where('order_status', 'DELIVERED')
         ->paginate($this->perVansale);
   }

   public function getPreOrderTotal()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
      return Orders::with('User', 'Customer')
         ->where('order_type', 'Pre Order')
         ->where(function ($query) use ($sidai) {
            $query->whereNull('supplierID')
               ->orWhere('supplierID', '')
               ->orWhere('supplierID', $sidai->id);
         })->whereBetween('updated_at', [$this->start, $this->end])
         ->paginate($this->perPreorder);
   }

   public function getActiveUserTotal()
   {
      return checkin::with('User', 'Customer')
         ->distinct('user_code')
         ->groupBy('user_code')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->paginate($this->perActiveUsers);
   }

   public function getOrderFullmentTotal()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();

      return Delivery::whereIn('delivery_status', ['Delivered', 'DELIVERED', 'Partial Delivery'])
         ->where(function ($query) use ($sidai) {
            $query->whereHas('Order', function ($subQuery) use ($sidai) {
               $subQuery->whereNull('supplierID')
                  ->where('supplierID', '=', '')
                  ->where('supplierID', '=', $sidai->id);
            })->whereHas('Order', function ($subQuery) {
               $subQuery->where('order_type', 'Pre Order');
            });
         })
      ->with('User', 'Customer')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->paginate($this->perOrderFulfilment);
   }

   public function getVisitsTotal()
   {
      return checkin::with('User', 'Customer')
         ->groupBy('customer_id')
         ->whereBetween('updated_at', [$this->start, $this->end])
         ->paginate($this->perVisits);
   }

   public function getCustomersCountTotal()
   {
      return customers::with('Area', 'Creator', 'Region')
         ->whereBetween('created_at', [$this->start, $this->end])
         ->paginate($this->perBuyingCustomer);
   }

   public function getGraphData()
   {
      $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
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

//      $preOrderCounts = Orders::where('order_type', 'Pre Order')
//         ->where(function ($query) use ($sidai) {
//            $query->whereNull('supplierID')
//               ->orWhere('supplierID', '')
//               ->orWhere('supplierID', $sidai->id);
//         })->whereYear('updated_at', '=', date('Y'))
//         ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
//         ->groupBy('month')
//         ->pluck('count', 'month')
//         ->toArray();

      $preOrderCounts = Orders::whereYear('updated_at', '=', date('Y'))
         ->selectRaw('MONTH(updated_at) as month, COUNT(*) as count')
         ->groupBy('month')
         ->pluck('count', 'month')
         ->toArray();
      $deliveryCounts = Delivery::whereIn('delivery_status', ['Delivered', 'DELIVERED', 'Partial Delivery'])
         ->where(function ($query) use ($sidai) {
            $query->whereHas('Order', function ($subQuery) use ($sidai) {
               $subQuery->whereNull('supplierID')
                  ->where('supplierID', '=', '')
                  ->where('supplierID', '=', $sidai->id);
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
      $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
      $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
      $this->start = $this->start == null ? $start_date : $this->start;
      $this->end = $this->end == null ? $end_date : $this->end;

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
         'orderfullmentTotal' => $this->getOrderFullmentTotal(),
         'visitsTotal' => $this->getVisitsTotal(),
         'customersCountTotal' => $this->getCustomersCountTotal(),
         'graphdata'=>$this->getGraphData()

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
      $this->getOrderFullmentTotal();
      $this->getVisitsTotal();
      $this->getCustomersCountTotal();
      $this->getGraphData();
   }
}
