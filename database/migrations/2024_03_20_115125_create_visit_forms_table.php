<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_forms', function (Blueprint $table) {
            $table->id();
           $table->string('name');
           $table->string('type');
           $table->enum('status', ['active', 'inactive'])->default('active');
           $table->string('description')->nullable();
           $table->json('fields');
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
        Schema::dropIfExists('visit_forms');
    }
}
