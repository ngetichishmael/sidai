<?php

namespace App\Http\Livewire\Products;

use App\Models\products\product_information;
use Auth;
use Livewire\Component;
use Livewire\WithPagination;

// use Illuminate\Support\Facades\Auth as FacadesAuth;

class Products extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 25;
   public ?string $search = null;
   public $orderBy = 'id';
   public $orderAsc = true;

   public function render()
   {

      $searchTerm = '%' . $this->search . '%';
      $products =  product_information::with('ProductPrice')->whereLike(
         [
            "parentID",
            "product_name",
            "supplierID",
            "track_inventory",
            "same_price",
            "short_description",
            "notification_email",
            "sku_code"
         ],
         $searchTerm
      )
         ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
         ->paginate($this->perPage);

      return view('livewire.products.products', compact('products'));
   }
}
