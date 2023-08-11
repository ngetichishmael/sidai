<?php

namespace App\Http\Livewire\Regional;

use App\Exports\RegionalExport;
use App\Models\Area;
use App\Models\customers;
use App\Models\Delivery;
use App\Models\Orders;
use App\Models\Region;
use App\Models\Subregion;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    protected $paginationTheme = 'bootstrap';
    public $start;
    public $end;
    use WithPagination;
    public function render()
    {
        $regions = Region::all();
        return view('livewire.regional.index', [
            'regions' => $regions,
        ]);
    }
    public function customers($id)
    {
        $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = customers::whereIn('route_code', $areas)->count();
        return $customers ?? 0;
    }
    public function orders($id)
    {
        $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = customers::whereIn('route_code', $areas)->pluck('id');
        $orders = Orders::whereIn('customerID', $customers)->count();
        return $orders ?? 0;
    }
    public function deliveries($id)
    {
        $subregions = Subregion::where('region_id', $id)->pluck('id');
        $areas = Area::whereIn('subregion_id', $subregions)->pluck('id');
        $customers = customers::whereIn('route_code', $areas)->pluck('id');
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
        $customers = customers::whereIn('route_code', $areas)->pluck('id');
        if ($customers->isEmpty()) {
            return $array;
        }
        return $customers->toArray();
    }
    public function export()
    {
        return Excel::download(new RegionalExport, 'RegionalReport.xlsx');
    }
}
