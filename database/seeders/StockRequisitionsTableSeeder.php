<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StockRequisitionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create sample stock requisitions
        // Create sample stock requisitions
        $stockRequisitions = [];

        for ($i = 1; $i <= 10; $i++) {
            $stockRequisitions[] = [
                'sales_person' => 'Sales Person ' . $i,
                'status' => 'Waiting Approval',
                'requisition_date' => Carbon::now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        // Insert seed data
        DB::table('stock_requisitions')->insert($stockRequisitions);
    }
}
