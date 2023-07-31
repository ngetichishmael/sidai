<?php

namespace App\Exports;

use App\Imports\customers;
use App\Models\Area;
use App\Models\customers as ModelsCustomers;
use App\Models\Delivery;
use App\Models\Orders;
use App\Models\Region;
use App\Models\Subregion;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class RegionalExport implements FromCollection, WithHeadings, WithMapping
{

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Replace 'orders' with the name of your orders table
        return Region::all();

    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Define the headings for your Excel file
        return ['ID', 'Region', 'Number of Orders', 'Number of Customers', 'Number of Deliveries'];
    }

    /**
     * @param mixed $order
     *
     * @return array
     */
    public function map($region): array
    {
        // Modify this mapping according to your Order and OrderItem models
        return [
            $region->id,
            $region->name,
            $this->orders($region->id) ?? 0,
            $this->customers($region->id) ?? 0,
            $this->deliveries($region->id) ?? 0,
        ];
    }
    public function customers($id)
    {
        $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = ModelsCustomers::whereIn('route_code', $areas)->count();
        return $customers ?? 0;
    }
    public function orders($id)
    {
        $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = ModelsCustomers::whereIn('route_code', $areas)->pluck('id');
        $orders = Orders::whereIn('customerID', $customers)->count();
        return $orders ?? 0;
    }
    public function deliveries($id)
    {
        $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = ModelsCustomers::whereIn('route_code', $areas)->pluck('id');
        $orders = Orders::whereIn('customerID', $customers)->pluck('order_code');
        $deliveries = Delivery::whereIn('order_code', $orders)->count();
        return $deliveries ?? 0;
    }
    public function filter(): array
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
        $customers = ModelsCustomers::whereIn('route_code', $areas)->pluck('id');
        if ($customers->isEmpty()) {
            return $array;
        }
        return $customers->toArray();
    }
}
