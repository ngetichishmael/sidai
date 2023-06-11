<?php

namespace App\Imports;

use App\Models\ProductInformation;
use App\Models\products\product_information;
use App\Models\products\product_inventory;
use App\Models\products\product_price;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToModel;

class ProductImport implements ToModel, WithValidation
{
   public function rules(): array
   {
      return [
         '0' => 'required|unique:product_information,product_name,NULL|string',
         '1' => 'required',
         '2' => 'required',
         '3' => 'required',
         '4' => 'required|mimes:png,jpg,bmp,gif,jpeg|max:5048',
      ];
   }

   public function model(array $row)
   {
      $imagePath = $row[4]->store('image', 'public');
      $productCode = Str::random(20);

      $productInformation = new product_information();
      $productInformation->product_name = $row[0];
      $productInformation->sku_code = Str::random(20);
      $productInformation->url = Str::slug($row[0]);
      $productInformation->brand = $row[2];
      $productInformation->supplierID = $row[3];
      $productInformation->warehouse_code = $row[6];
      $productInformation->image = $imagePath;
      $productInformation->track_inventory = 'Yes';
      $productInformation->business_code = Auth::user()->business_code;
      $productInformation->created_by = Auth::user()->user_code;
      // Set other fields accordingly
      // ...

      $productInventory = new product_inventory();
      $productInventory->product_code = $productCode;
      $productInventory->current_stock = 0;
      $productInventory->reorder_point = 0;
      $productInventory->reorder_qty = 0;
      // Set other fields accordingly
      // ...

      $productPrice = new product_price();
      $productPrice->product_code = $productCode;
      $productPrice->buying_price = $row[1];
      $productPrice->selling_price = $row[2];
      $productPrice->distributor_price = $row[3];
      // Set other fields accordingly
      // ...

      return [
         'productInformation' => $productInformation,
         'productInventory' => $productInventory,
         'productPrice' => $productPrice,
      ];
   }
}
