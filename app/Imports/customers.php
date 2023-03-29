<?php

namespace App\Imports;

use App\Models\Area;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\customer\customers as customer;
use App\Models\Region;
use App\Models\Subregion;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;

class customers implements ToCollection, WithHeadingRow
{
   /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
   {
      foreach ($rows as $row) {
         $region = Region::UpdateOrCreate(
            [
               'name' => ucwords($row['region'])
            ],
            [
               'primary_key' => Str::random(20)
            ]
         );
         $subregion = Subregion::UpdateOrCreate(
            [

               'name' => ucwords($row['subregion']),
            ],
            [
               'region_id' => $region->id,
               'primary_key' => Str::random(20)
            ]
         );
         $area = Area::UpdateOrCreate(
            [
               'name' => ucwords(strtolower($row['area'])),
            ],
            [
               'subregion_id' => $subregion->id,
               'primary_key' => Str::random(20)
            ]
         );
         $customer = new customer;
         $customer->customer_name = ucwords($row['customer_name']);
         $customer->phone_number = $row['phone_number'];
         $customer->account = $row['account'];
         $customer->address = $area->name . ', ' . $subregion->name . ' ' . $region->name;
         $customer->latitude = $row['latitude'];
         $customer->longitude = $row['longitude'];
         $customer->contact_person = ucwords($row['contact_person']);
         $customer->customer_group = $row['customer_group'];
         $customer->price_group = $row['price_group'];
         $customer->route = $area->id;
         $customer->region_id = $region->id;
         $customer->subregion_id = $subregion->id;
         $customer->zone_id = $area->id;
         $customer->route = $area->id;
         $customer->approval = 'Approved';
         $customer->status = 'Active';
         $customer->customer_secondary_group = $row['customer_secondary_group'];
         //$customer->telephone = $row['telephone'];
         //$customer->manufacturer_number = $row['manufacturer_number'];
         // $customer->vat_number = $row['vat_number'];
         // $customer->delivery_time = $row['delivery_time'];
         // $customer->city = $row['city'];
         // $customer->province = $row['province'];
         // $customer->postal_code = $row['postal_code'];
         // $customer->country = $row['country'];
         // $customer->customer_secondary_group = $row['customer_secondary_group'];
         // $customer->branch = $row['branch'];
         // $customer->email = $row['email'];
         // $customer->phone_number = $row['phone_number'];
         $customer->business_code = Auth::user()->business_code;
         $customer->created_by = Auth::user()->user_code;
         $customer->updated_by = Auth::user()->id;
         $customer->save();
      }
   }
}
