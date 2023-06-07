<?php

namespace App\Http\Livewire\Dashboard;

use App\Models\customer\checkin;
use App\Models\Orders;
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
        // dd($this->start);
       $startDate = Carbon::now()->subMonth();
       $endDate = Carbon::now();

        $vansales = Orders::where('order_type', 'Van sales')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->where('order_status', 'DELIVERED')
            ->sum('price_total');
        $vansalesTotal = Orders::with('user', 'customer')
            ->where('order_type', 'Van sales')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->where('order_status', 'DELIVERED')
            ->paginate($this->perVansale);

        $preorder = Orders::where('order_type', 'Pre Order')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->where('order_status', 'DELIVERED')
            ->sum('price_total');
        $preorderTotal = Orders::with('user', 'customer')
            ->where('order_type', 'Pre Order')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->where('order_status', 'DELIVERED')
            ->paginate($this->perPreorder);
        $orderfullment = Orders::where('order_status', 'DELIVERED')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->count();
        $orderfullmentTotal = Orders::with('user', 'customer')
            ->where('order_status', 'DELIVERED')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perOrderFulfilment);
        $activeUser = DB::table('customer_checkin')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->distinct('user_code')
            ->count();
        $activeUserTotal = checkin::with('user', 'customer')
            ->distinct('user_code')
            ->groupBy('user_code')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perActiveUsers);
        $strike = DB::table('customer_checkin')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->count();

        $visitsTotal = checkin::with('user', 'customer')
            ->groupBy('customer_id')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perVisits);



        $activeAll = User::where('account_type', 'Sales')
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

       $preOrders = Orders::where('order_type', 'pre-orders')
          ->where('order_status', 'delivered')
          ->whereBetween('created_at', [$startDate, $endDate])
          ->selectRaw('DATE(created_at) AS date, COUNT(*) AS count')
          ->groupBy('date')
          ->orderBy('date')
          ->get();
//dump($preOrders);
// Retrieve delivered orders for the last one month
       $deliveredOrders = Orders::where('order_status', 'delivered')
          ->whereBetween('delivery_date', [$startDate, $endDate])
          ->selectRaw('DATE(delivery_date) AS date, COUNT(*) AS count')
          ->groupBy('delivery_date')
          ->orderBy('delivery_date')
          ->get();
//       dump($deliveredOrders);
       $preOrdersLabels = $preOrders->pluck('date')->toArray();
       $preOrdersData = $preOrders->pluck('count')->toArray();
//       dump($preOrdersData, $preOrdersLabels);

       $deliveredOrdersLabels = $deliveredOrders->pluck('date')->toArray();
       $deliveredOrdersData = $deliveredOrders->pluck('count')->toArray();
//       dump($deliveredOrdersData, $deliveredOrdersLabels);


        $customersCount = Orders::distinct('customerID')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->count();
        $customersCountTotal = Orders::with('user', 'customer')
            ->groupBy('customerID')
            ->distinct('customerID')
            ->whereBetween('updated_at', [$this->start, $this->end])
            ->paginate($this->perBuyingCustomer);


        return view('livewire.dashboard.dashboard', [
            'Cash' => $cash,
            'Mpesa' => $mpesa,
            'Cheque' => $cheque,
            'sales' => $sales,
            'total' => $cash + $cheque + $mpesa,
            'vansales' => $vansales,
            'preorder' => $preorder,
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
           'deliveredOrdersData'=>$deliveredOrdersData,
           'deliveredOrdersLabels'=>$deliveredOrdersLabels,
           'preOrdersLabels'=>$preOrdersLabels,
           'preOrdersData'=>$preOrdersData ,
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
