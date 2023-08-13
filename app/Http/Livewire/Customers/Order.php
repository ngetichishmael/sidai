<?php

namespace App\Http\Livewire\Customers;

use App\Models\Cart;
use App\Models\customers;
use App\Models\Order_items;
use App\Models\Orders;
use App\Models\products\product_information;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Livewire\Component;

class Order extends Component
{

   public $customer_id;
   public $Orders;
   public $orderOne;
   public bool $countOrders = true;
   protected $rules = [
      'Orders.*.id' => 'required',
      'Orders.*.quantity' => 'required',
   ];
   public function render()
   {
      $customer = customers::whereId($this->customer_id)->first();
      return view('livewire.customers.order', [
         'id' => $customer->customer_name
      ]);
   }
   public function mount()
   {


      $this->orderOne = product_information::get();
      $this->fill([
         'Orders' => collect([
            [
               'id' => 0,
               'product_name' => '',
               'quantity' => 1
            ]
         ]),
      ]);
   }
   public function addOrders()
   {
      $this->Orders->push(new product_information());
      $this->countOrders = true;
   }

   public function removeOrders($index)
   {
      $this->Orders->pull($index);
      if (count($this->Orders) < 1) {
         $this->countOrders = false;
      }
   }
   public function submit()
   {
      $total = 0;
      $checkinCode = Str::random(20);
      $random = Str::random(10);
      $user_code = Auth::user()->user_code;
      foreach ($this->Orders as $value) {
         $product = product_information::with('ProductPrice')->whereId($value["id"])->first();
         $price_total = $value["quantity"] * $product->ProductPrice->selling_price;
         $total += $price_total;
         Cart::updateOrCreate(
            [
               'checkin_code' => $checkinCode,
               "order_code" => $random,
            ],
            [
               'productID' => $value["id"],
               "product_name" => $product->product_name,
               "qty" => $value["quantity"] ?? 1,
               "price" => $product->ProductPrice->selling_price,
               "amount" => $value["quantity"] ?? 1 * $product->ProductPrice->selling_price,
               "total_amount" => $value["quantity"] * $product->ProductPrice->selling_price,
               "userID" => $user_code,
            ]
         );
         Orders::updateOrCreate(
            [

               'order_code' => $random,
            ],
            [
               'user_code' => $user_code,
               'customerID' => $this->customer_id,
               'price_total' => $total,
               'balance' => $total,
               'order_status' => 'Pending Delivery',
               'payment_status' => 'Pending Payment',
               'qty' => $value["quantity"] ?? 1,
               'discount' => "0",
               'checkin_code' => $checkinCode,
               'order_type' => 'Van sales',
               'delivery_date' => now(),
               'business_code' => Auth::user()->business_code,
               'updated_at' => now(),
            ]
         );
         Order_items::create([
            'order_code' => $random,
            'productID' => $value["id"],
            'product_name' => $product->product_name,
            'quantity' => $value["quantity"] ?? 1,
            'sub_total' => $value["quantity"] ?? 1 * $product->ProductPrice->selling_price,
            'total_amount' => $value["quantity"] ?? 1 * $product->ProductPrice->selling_price,
            'selling_price' => $product->ProductPrice->selling_price,
            'discount' => "0",
            'taxrate' => 0,
            'taxvalue' => 0,
            'created_at' => now(),
            'updated_at' => now(),
         ]);
      }
      return redirect()->to('/orders');
   }
}
