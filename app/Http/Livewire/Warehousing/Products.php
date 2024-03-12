<?php

namespace App\Http\Livewire\Warehousing;

use App\Models\products\product_information;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;

class Products extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;
   public $warehouse;

   public function mount($warehouse)
   {
      $this->warehouse = $warehouse;
   }
    public function render()
    {
//       $products = product_information::with('Inventory', 'ProductPrice', 'ProductSKU')->where('warehouse_code', $this->warehouse)->paginate($this->perPage);

       $query = product_information::with('Inventory', 'ProductPrice', 'ProductSKU')
          ->where('warehouse_code', $this->warehouse);

       if ($this->search) {
          $query->where('product_name', 'like', '%' . $this->search . '%');
       }

       $products = $query->orderBy($this->orderBy, $this->orderAsc ? 'asc' : 'desc')
          ->paginate($this->perPage);
        return view('livewire.warehousing.products', ['products'=>$products]);
    }
    public function export()
    {
      $alldata = product_information::with('Inventory', 'ProductPrice', 'ProductSKU')
      ->where('warehouse_code', $this->warehouse);

   if ($this->search) {
      $query->where('product_name', 'like', '%' . $this->search . '%');
   }
   $data = $alldata->get();
       return Excel::download(new InventoryExport($data), 'products.xlsx');
    }
}
