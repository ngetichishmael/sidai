<?php

namespace App\Exports;

use App\Models\Orders;
use App\Models\suppliers\suppliers;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class DistributorExport implements FromView
{

    /**
    * @return \Illuminate\Support\FromView
    */
    public function view(): View
    {
         return view('Exports.exceldistributors', [
            'distributors' => $this->getData()
         ]);

    }
    public function getData($fromDate = null, $toDate = null) {
        $query = suppliers::withCount('orders')
            ->withCount('OrdersDelivered')
            ->whereNotIn('name', ['sidai', 'Sidai', 'SIDAI','Sidai Warehouse']);
    
        if ($fromDate) {
            $query->whereDate('created_at', '>=', $fromDate);
        }
    
        if ($toDate) {
            $query->whereDate('created_at', '<=', $toDate);
        }
    
        $data = $query->get();
    
        return $data;
    }
}
