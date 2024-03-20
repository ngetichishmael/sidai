<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitFormSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visit_form_submissions', function (Blueprint $table) {
            $table->id();
           $table->string('form_type');
           $table->unsignedBigInteger('user_id');
           $table->string('staff_name');
           $table->string('location');
           $table->string('region');
           $table->json('form_data');
            $table->timestamps();
           $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visit_form_submissions');
    }
}
