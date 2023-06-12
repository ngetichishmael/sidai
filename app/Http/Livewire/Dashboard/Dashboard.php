<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\customer\checkin;
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
    public function render()
    {
        $start_date = Carbon::now()->startOfMonth()->format('Y-m-d');
        $end_date = Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->start = $this->start == null ? $start_date : $this->start;
        $this->end = $this->end == null ? $end_date : $this->end;
       $sidai = suppliers::whereIn('name', ['Sidai', 'SIDAI', 'sidai'])->first();
        // dd($this->start);
        $vansales = Orders::where('order_type', 'Van sales')
            ->whereBetween('updated_at', [$this->start, $this->end])
           ->whereIn('supplierID', [$sidai->id, '', null])
            ->where('order_status', 'DELIVERED')
            ->sum('price_total');
        $vansalesTotal = Orders::with('User', 'Customer')
            ->where('order_type', 'Van sales')
           ->whereIn('supplierID', [$sidai->id, '', null])
           ->whereIn('supplierID', [$sidai->id, '', null])
            ->whereBetween('created_at', [$this->start, $this->end])
            ->where('order_status', 'DELIVERED')
            ->paginate($this->perVansale);

        $preorder = Orders::where('order_type', 'Pre Order')
           ->whereIn('supplierID', [$sidai->id, '', null])
            ->whereBetween('updated_at', [$this->start, $this->end])
//            ->where('order_status', 'DELIVERED')
            ->count();
        $preorderTotal = Orders::with('User', 'Customer')
            ->where('order_type', 'Pre Order')
           ->whereIn('supplierID', [$sidai->id, '', null])
            ->whereBetween('updated_at', [$this->start, $this->end])
//            ->where('order_status', '')
            ->paginate($this->perPreorder);
        $orderfullment = Orders::where('order_status', 'DELIVERED')
           ->where('order_type', 'Pre Order')
           ->whereIn('supplierID', [null, '', $sidai->id])
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->count();
        $orderfullmentbydistributors = Orders::whereIn('order_status', ['DELIVERED','Delivered'])
           ->where('supplierID', '!=', $sidai->id)
           ->where('order_type', 'Pre Order')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->count();
        $orderfullmentbydistributorspage = Orders::with('Customer', 'user', 'distributor')
           ->whereIn('order_status', ['DELIVERED','Delivered'])
           ->where('supplierID', '!=', $sidai->id)
           ->where('order_type', 'Pre Order')
            ->whereBetween('updated_at', [$this->start, $this->end])
           ->paginate($this->perPreorder);
        $orderfullmentTotal = Orders::with('User', 'Customer')
           ->whereIn('supplierID', [$sidai->id, '', null])
            ->where('order_status', 'DELIVERED')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perOrderFulfilment);
        $activeUser = DB::table('customer_checkin')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->distinct('user_code')
            ->count();
        $activeUserTotal = checkin::with('User', 'Customer')
            ->distinct('user_code')
            ->groupBy('user_code')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perActiveUsers);
        $strike = DB::table('customer_checkin')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->count();

        $visitsTotal = checkin::with('User', 'Customer')
            ->groupBy('customer_id')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perVisits);



        $activeAll =  User::where('status', 'Active')
           ->where('account_type', '!=','Customer')
           ->count();
        $sales = DB::table('order_payments')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->select('id', 'amount', 'balance', 'payment_method', 'isReconcile', 'user_id')
            ->sum('balance');

        $cash = OrderPayment::where('payment_method', 'PaymentMethods.Cash')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->sum('amount');
        $mpesa = OrderPayment::where('payment_method', 'PaymentMethods.Mpesa')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->sum('amount');
        $cheque = OrderPayment::where('payment_method', 'PaymentMethods.Cheque')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->sum('amount');

// Retrieve pre-order counts per month

       $preOrderCounts = Orders::where('order_type', 'Pre Order')
          ->whereIn('supplierID', [$sidai->id, '', null])
          ->where('order_status', 'DELIVERED')
          ->whereYear('created_at', '=', date('Y'))
          ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
          ->groupBy('month')
          ->pluck('count', 'month')
          ->toArray();
       // Retrieve delivery counts per month
       $deliveryCounts = Delivery::whereIn('delivery_status', ['Delivered', 'Partial Delivery'])
          ->whereYear('created_at', '=', date('Y'))
          ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
          ->groupBy('month')
          ->pluck('count', 'month')
          ->toArray();
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

       // Combine pre-order and delivery counts with month names
       $graphdata = [];
       for ($month = 1; $month <= 12; $month++) {
          $graphdata[] = [
             'month' => $months[$month],
             'preOrderCount' => $preOrderCounts[$month] ?? 0,
             'deliveryCount' => $deliveryCounts[$month] ?? 0,
          ];
       }

        $customersCount = Orders::groupBy('customerID')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->count();
        $customersCountTotal = Orders::with('User', 'Customer')
            ->groupBy('customerID')
//            ->distinct('customerID')
            ->whereBetween('created_at', [$this->start, $this->end])
            ->paginate($this->perBuyingCustomer);


        return view('livewire.dashboard.dashboard', [
            'Cash' => $cash,
            'Mpesa' => $mpesa,
            'Cheque' => $cheque,
            'sales' => $sales,
            'total' => $cash + $cheque + $mpesa,
            'vansales' => $vansales,
            'preorder' => $preorder,
            'orderfullmentbydistributors'=>$orderfullmentbydistributors,
            'orderfullmentbydistributorspage'=>$orderfullmentbydistributorspage,
            'orderfullment' => $orderfullment,
            'activeUser' => $activeUser,
            'activeAll' => $activeAll,
            'strike' => $strike,
            'customersCount' => $customersCount,
            'vansalesTotal' => $vansalesTotal,
            'preorderTotal' => $preorderTotal,
            'activeUserTotal' => $activeUserTotal,
            'orderfullmentTotal' => $orderfullmentTotal,
            'visitsTotal' => $visitsTotal,
            'customersCountTotal' => $customersCountTotal,
           'graphdata'=>$graphdata
        ]);
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
        $this->mount();
        $this->render();
    }
    public function updatedEnd()
    {
        $this->mount();
        $this->render();
    }
}
