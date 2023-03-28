<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\products\product_price;
use App\Models\products\product_inventory;
use App\Models\products\product_information;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Auth as FacadesAuth;

class products implements ToCollection, WithHeadingRow
{
   /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
   {
      foreach ($rows as $row) {
         // dd($row["product_code"]);

         if (isset($row["product_code_distributer"])) {
            $this->distributer($row);
         }
         if (isset($row["product_code_wholesale"])) {
            $this->wholesale($row);
         }
         if (isset($row["product_code_retail"])) {
            $this->retail($row);
         }
      }
   }
   public function distributer($row)
   {
      $product = product_information::updateOrCreate([
         "sku_code" => $row['product_code_distributer'],
         "batch_code" => $row['product_code_distributer'],
      ], [
         "product_name" => $row['product_name'],
         "brand" => "SIDAI",
         "category" => "SIDAI",
         "description" => $row['product_name'],
         "business_code" => Auth::user()->business_code,
         "created_by" => Auth::user()->id,
      ]);

      product_price::updateOrcreate(
         [
            "productID" => $product->id,
            "product_code" => $row['product_code_distributer'],

         ],
         [
            "default_price" => $row["price"],
            "business_code" => Auth::user()->business_code,
            "created_by" => Auth::user()->id,
         ]
      );
      product_inventory::updateOrCreate(
         [
            "productID" => $product->id,
         ],
         [
            "default_inventory" => 'Yes',
            "current_stock" => 0,
            "business_code" => Auth::user()->business_code,
            "created_by" => Auth::user()->id,
         ]
      );
      //product price
      // $product_price = new product_price;
      // $product_price->productID = $product->id;
      // $product_price->product_code = $row['product_code_distributer'];
      // $product_price->selling_price = (int)$row['selling_price'] ?? "0";
      // $product_price->buying_price = (int)$row['buying_price'] ?? "0";
      // $product_price->branch_id = $row['region'] ?? "1";
      // $product_price->business_code = Auth::user()->business_code;
      // $product_price->created_by = Auth::user()->id;
      // $product_price->save();

      // //product quantities
      // $product_inventory = new product_inventory;
      // $product_inventory->current_stock = 0;
      // $product_inventory->productID = $product->id;
      // $product_inventory->default_inventory = 'Yes';
      // $product_inventory->business_code = Auth::user()->business_code;
      // $product_inventory->created_by = Auth::user()->id;
      // $product_inventory->save();
   }
   public function wholesale($row)
   {
      $product = product_information::updateOrCreate([
         "sku_code" => $row['product_code_wholesale'],
         "batch_code" => $row['product_code_wholesale'],
      ], [
         "product_name" => $row['product_name'],
         "brand" => "SIDAI",
         "category" => "SIDAI",
         "description" => $row['product_name'],
         "business_code" => Auth::user()->business_code,
         "created_by" => Auth::user()->id,
      ]);

      product_price::updateOrcreate(
         [
            "productID" => $product->id,
            "product_code" => $row['product_code_wholesale'],

         ],
         [
            "selling_price" => $row["price"],
            "business_code" => Auth::user()->business_code,
            "created_by" => Auth::user()->id,
         ]
      );
      product_inventory::updateOrCreate(
         [
            "productID" => $product->id,
         ],
         [
            "default_inventory" => 'Yes',
            "current_stock" => 0,
            "business_code" => Auth::user()->business_code,
            "created_by" => Auth::user()->id,
         ]
      );
   }
   public function retail($row)
   {
      $product = product_information::updateOrCreate([
         "sku_code" => $row['product_code_retail'],
         "batch_code" => $row['product_code_retail'],
      ], [
         "product_name" => $row['product_name'],
         "brand" => "SIDAI",
         "category" => "SIDAI",
         "description" => $row['product_name'],
         "business_code" => Auth::user()->business_code,
         "created_by" => Auth::user()->id,
      ]);

      product_price::updateOrcreate(
         [
            "productID" => $product->id,
            "product_code" => $row['product_code_retail'],

         ],
         [
            "buying_price" => $row["price"],
            "business_code" => Auth::user()->business_code,
            "created_by" => Auth::user()->id,
         ]
      );
      product_inventory::updateOrCreate(
         [
            "productID" => $product->id,
         ],
         [
            "default_inventory" => 'Yes',
            "current_stock" => 0,
            "business_code" => Auth::user()->business_code,
            "created_by" => Auth::user()->id,
         ]
      );
   }
}