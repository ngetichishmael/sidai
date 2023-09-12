<?php

namespace App\Http\Livewire\Stocks;

use App\Models\warehouse_assign;
use App\Models\warehousing;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Reconciliation extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 10;
    public $orderBy = 'id';
    public $orderAsc = true;
    public ?string $search = null;
    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
      $warehouses = warehousing::with('manager', 'region', 'subregion','ReconciledProducts');
//         ->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')->simplePaginate($this->perPage);
       if (strcasecmp(Auth::user()->account_type, 'Shop-Attendee') == 0) {
          $check = warehouse_assign::where('manager', Auth::user()->user_code)
             ->select('warehouse_code')
             ->pluck('warehouse_code');
          $warehouses = $warehouses->whereIn('warehouse_code', $check)
             ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
             ->paginate($this->perPage);
       } else if ((strcasecmp(Auth::user()->account_type, 'RSM') == 0)) {
          $warehouses = $warehouses->where('region_id', Auth::user()->region_id)
             ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
             ->paginate($this->perPage);
       }else{
          $warehouses = $warehouses->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
             ->paginate($this->perPage);
       }

        return view('livewire.stocks.reconciliation',['warehouses' => $warehouses]);
    }
}
