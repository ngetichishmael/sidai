<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RequisitionProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requisitionProducts = [];

        for ($i = 1; $i <= 10; $i++) {
            $requisitionProducts[] = [
                'product_id' => $i,
                'requisition_id' => $i,
                'quantity' => rand(1, 10),
                'stock_requisition_id' => $i,
                'product_information_id' => $i,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert seed data
        DB::table('requisition_products')->insert($requisitionProducts);
    }
}
