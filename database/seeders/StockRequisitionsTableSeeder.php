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
             // Get random user IDs from the 'users' table
        $numberOfRequisitions = 10; // Number of stock requisitions to generate
        $userIds = DB::table('users')->where('account_type','Sales')->inRandomOrder()->limit($numberOfRequisitions)->pluck('id');
        DB::table('stock_requisitions')->truncate();
        // Create sample stock requisitions
        $stockRequisitions = [];

        foreach ($userIds as $userId) {
            $stockRequisitions[] = [
                'user_id' => $userId,
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
