<?php

namespace App\Http\Livewire\Products;

use App\Models\products\product_information;
use Livewire\Component;
// use Illuminate\Support\Facades\Auth as FacadesAuth;
use Livewire\WithPagination;
use Auth;

class Products extends Component
{
   use WithPagination;
   protected $paginationTheme = 'bootstrap';
   public $perPage = 10;
   public ?string $search = null;
   public $orderBy = 'id';
   public $orderAsc = true;

   public function render()
   {

      $searchTerm = '%' . $this->search . '%';
      $products =  product_information::with('ProductPrice')->where('is_approved', 'Yes')->whereLike(
         [
            "parentID",
            "product_name",
            "sku_code",
            "brand",
            "supplierID",
            "track_inventory",
            "same_price",
            "short_description",
            "notification_email",
         ],
         $searchTerm
      )
         ->orderBy($this->orderBy, $this->orderAsc ? 'desc' : 'asc')
         ->paginate($this->perPage);

      return view('livewire.products.products', compact('products'));
   }
}
