<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
   {
      Schema::create('messages', function (Blueprint $table) {
         $table->id();
         $table->integer('ticket_id');
         $table->string('sender_code');
         $table->text('message');
         $table->enum('read', [1, 0])->default(0);
         $table->integer('parent_id')->nullable();
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
      Schema::dropIfExists('messages');
   }
}
