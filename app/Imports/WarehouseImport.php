<?php

namespace App\Imports;

use App\Models\warehousing;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class WarehouseImport implements ToCollection, WithHeadingRow
{
   /**
    * @param Collection $collection
    */
   public function collection(Collection $collection)
   {
      foreach ($collection as $row) {
         warehousing::updateOrCreate(
            [
               'business_code' => Auth::user()->business_code,
               'name' => $row['customer'],
            ],
            [
               'warehouse_code' => Str::random(20),
               'country' => "Kenya",
               'city' => $row['region'],
               'location' => $row['location'],
               'phone_number' => $row['contact'],
               'email' => $row['email'],
               'longitude' => "0.0000",
               'latitude' => "0.0000",
               'status' => "Active",
               'is_main' => "Yes",
               'created_by' => Auth::user()->user_code,
            ]
         );
      }
   }
}
