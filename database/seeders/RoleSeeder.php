<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

       public function run()
    {
       Role::create(['name' => 'Admin', 'description' => 'Administrator', 'data_access_level' => 'regional']);
       Role::create(['name' => 'NSM', 'description' => 'National Sales Manager', 'data_access_level' => 'regional']);
       Role::create(['name' => 'RSM', 'description' => 'Regional Sales Manager', 'data_access_level' => 'regional']);
       Role::create(['name' => 'TSR', 'description' => 'Trade Sales Representative', 'data_access_level' => 'regional']);
       Role::create(['name' => 'TD', 'description' => 'Trade Developer', 'data_access_level' => 'subregional']);
       Role::create(['name' => 'Shop_Attendee', 'description' => 'Shop Attendee', 'data_access_level' => 'subregional']);


//       Role::create(['name' => 'admin', 'description' => 'Administrator', 'data_access_level' => 'regional']);
//       Role::create(['name' => 'shop_attendee_dashboard', 'description' => 'Shop Attendee Dashboard', 'data_access_level' => 'subregional']);
//       Role::create(['name' => 'manager_dashboard', 'description' => 'Manager Dashboard', 'data_access_level' => 'subregional']);
//       Role::create(['name' => 'managers_app', 'description' => 'Managers App', 'data_access_level' => 'subregional']);
//       Role::create(['name' => 'sales_app', 'description' => 'Sales App', 'data_access_level' => 'subregional']);


    }

}
