<?php
namespace Database\Seeders;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
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
         // Get product IDs from the 'products' table
         $productIds = DB::table('product_information')->pluck('id');

         // Get stock requisition IDs from the 'stock_requisitions' table
         $stockRequisitionIds = DB::table('stock_requisitions')->pluck('id');
         DB::table('requisition_products')->truncate();
         // Create sample requisition products
         $requisitionProducts = [];

         foreach ($stockRequisitionIds as $requisitionId) {
             for ($i = 0; $i < 3; $i++) { // Generate 3 requisition products per stock requisition
                 $productId = $productIds->random();

                 $requisitionProducts[] = [
                     'product_id' => $productId,
                     'requisition_id' => $requisitionId,
                     'quantity' => rand(1, 10),
                     'stock_requisition_id' => $requisitionId,
                     'product_information_id' => $productId, // Assuming product_information_id is the same as product_id
                     'created_at' => Carbon::now(),
                     'updated_at' => Carbon::now(),
                 ];
             }
         }

         // Insert seed data
         DB::table('requisition_products')->insert($requisitionProducts);
    }
}
