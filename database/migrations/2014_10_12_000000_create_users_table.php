<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
   /**
    * Run the migrations.
    *
    * @return void
    */
   public function up()
   {
      Schema::create('users', function (Blueprint $table) {
         $table->id();
         $table->string('user_code');
         $table->string('name');
         $table->string('email')->unique();
         $table->timestamp('email_verified_at')->nullable();
         $table->string('password');
         $table->rememberToken();
         $table->char('businessID')->nullable();
         $table->char('phone_number')->nullable();
         $table->char('location')->nullable();
         $table->char('gender')->nullable();
         $table->char('status')->nullable();
         $table->integer('connection_id');
         $table->enum('user_status', ['Offline', 'Online']);
         $table->string('fcm_token')->nullable();
         $table->text('block_reason')->nullable();
         $table->text('admin_id')->nullable();
         $table->timestamps();
      });
   }

   /**
    * Reverse the migrations.
    *
    * @return void
    */
   public function down()
   {
      Schema::dropIfExists('users');
   }
}
