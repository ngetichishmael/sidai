<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([

            'name' => 'Fall Back',
            'email' => 'admin@admin.com',
            'role_id' => 1,
            'status' => '1',
            'password' => '$2a$12$8ZoZ2ze5Ra7zRZ4j5pmL2.GCBUdmgatCu5tQWr85r42XKYncBNFKa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('users')->insert([

            'name' => 'steven',
            'email' => 'stevenmaina3131@gmail.com',
            'role_id' => 1,
            'status' => '1',
            'password' => '$$2a$12$8ZoZ2ze5Ra7zRZ4j5pmL2.GCBUdmgatCu5tQWr85r42XKYncBNFKa',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
