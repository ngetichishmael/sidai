<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableSupportTickets extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
   {
      Schema::create('support_tickets', function (Blueprint $table) {
         $table->id();
         $table->string('subject');
         $table->string('user_code')->nullable();
         $table->string('customer_code');
         $table->text('description')->nullable();
         $table->enum('status', ['open', 'closed'])->default('open');
         $table->enum('read', [1, 0])->default(0);
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
       Schema::dropIfExists('support_tickets');
    }
}
