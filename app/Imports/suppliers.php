<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Models\suppliers\supplier_address as SuppliersSupplier_address;
use App\Models\suppliers\suppliers as SuppliersSuppliers;
use Illuminate\Support\Facades\Auth;

class suppliers implements ToCollection, WithHeadingRow
{
   /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
   {
      foreach ($rows as $row) {
         SuppliersSuppliers::updateOrCreate(
            [
               'name' => $row['customer'],
               'business_code' => Auth::user()->business_code,
            ],
            [
               "category" => "Non-Inclusive",
               'email' => $row['email'] ?? "sidai@sokoflow.com",
               'phone_number' => $row['contact'] ?? "sidai@sokoflow.com",
               'telephone' => $row['contact'],
               'created_by' => Auth::user()->id,
               'updated_by' => Auth::user()->id,
            ]
         );


         // SuppliersSupplier_address::updateOrCreate(
         //    [
         //       'supplierID' => $supplier->id,
         //    ],
         //    [
         //       'bill_attention' => $row['customer'],
         //       'bill_street' => $row['region'],
         //       'bill_state' => $row['location'],
         //       'bill_city' => $row['region'],
         //       'bill_zip_code' => "+254",
         //       'bill_country' => 110,
         //       'bill_postal_address' => $row['email'],
         //    ]
         // );
      }
   }
}
