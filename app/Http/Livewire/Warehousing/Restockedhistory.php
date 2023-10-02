<?php

namespace App\Http\Livewire\Warehousing;

use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\ProductSku;
use Livewire\Component;
use Livewire\WithPagination;

class Restockedhistory extends Component
{ use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public $orderBy = 'id';
   public $orderAsc = true;
   public ?string $search = null;
   public $warehouse;
   public $productid;

   public function mount($warehouse, $productid)
   {
      $this->warehouse = $warehouse;
      $this->productid = $productid;
   }
   public function render()
   {
      $pid = product_inventory::where('productID', $this->productid)->first();
      $restockings = ProductSku::with('restockedBy','addedBy')
         ->join('product_information', 'product_skus.sku_code', '=', 'product_information.sku_code')
         ->where('product_skus.warehouse_code', $this->warehouse)
         ->where('product_skus.product_inventory_id', $pid->id)
         ->orderBy('product_skus.id', $this->orderAsc ? 'asc' : 'desc')
         ->paginate($this->perPage);
      return view('livewire.warehousing.restockedhistory', ['restockings'=>$restockings]);
   }
}
