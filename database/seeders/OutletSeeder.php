<?php

namespace Database\Seeders;

use App\Models\OutletType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class OutletSeeder extends Seeder
{
   /**
    * Run the database seeds.
    *
    Spare Part Shops
Garages
Independent Network
Wholesalers
Motorcycle dealer
Transporter
Hardware Store
Agri Consumer
    * @return void
    */
   public $data = [
      "Qx4FstqLJfHwf3WA" => "Independent Network",
      "Qx4FstqLJfHwf3WA" => "Spare Part Shops",
      "Qx4FstqLJfHwf3WA" => "Garages",
      "Qx4FstqLJfHwf3WA" => "Wholesalers",
      "Qx4FstqLJfHwf3WA" => "Motorcycle dealer",
      "Qx4FstqLJfHwf3WA" => "Transporter",
      "Qx4FstqLJfHwf3WA" => "Hardware Store",
      "Qx4FstqLJfHwf3WA" => "Agri Consumer",
   ];
   public function run()
   {
      foreach ($this->data as $key => $value) {
         $random = Str::random(20);
         OutletType::create(
            [

               "outlet_code" => $random,
               "business_code" => $key,
               "outlet_name" => $value
            ]
         );
      }
   }
}
