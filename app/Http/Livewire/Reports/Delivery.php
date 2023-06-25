<?php

namespace App\Http\Livewire\Reports;

use App\Exports\DeliveryExport;
use App\Models\Orders;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class Delivery extends Component
{
    public function render()
    {
        $count = 1;
       $deliveries = Orders::with('User', 'Customer')->where('order_status', 'Delivered')->get();
        return view('livewire.reports.delivery', ['deliveries' => $deliveries, 'count' => $count]);
    }
    public function export()
   {
      return Excel::download(new DeliveryExport, 'DeliveryReport.xlsx');
   }
}
