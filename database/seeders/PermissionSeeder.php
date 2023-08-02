<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
   public function run()
   {
//      Permission::create(['name' => 'admin_dashboard', 'description' => 'Access Admin Dashboard']);
//       Permission::create(['name' => 'manager_dashboard', 'description' => 'Access Manager Dashboard']);
//       Permission::create(['name' => 'manager_app', 'description' => 'Access Manager App']);
//       Permission::create(['name' => 'sales_app', 'description' => 'Access Sales App']);
//      Permission::create(['name' => 'shop_attendee_dashboard', 'description' => 'Access Shop Attendee Dashboard']);


      // Dashboard permissions
      Permission::create(['name' => 'admin_dashboard', 'description' => 'Access Admin Dashboard', 'type' => 'dashboard']);
      Permission::create(['name' => 'shop_attendee_dashboard', 'description' => 'Access Shop Attendee Dashboard', 'type' => 'dashboard']);
      Permission::create(['name' => 'manager_dashboard', 'description' => 'Access Manager Dashboard', 'type' => 'dashboard']);

      // API permissions
      Permission::create(['name' => 'managers_app_api', 'description' => 'Access Managers App API', 'type' => 'api']);
      Permission::create(['name' => 'sales_app_api', 'description' => 'Access Sales App API', 'type' => 'api']);

   }
}
