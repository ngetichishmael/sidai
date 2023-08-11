<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::create('warehouse_product', function (Blueprint $table) {
          $table->id();
          $table->unsignedBigInteger('product_id');
          $table->string('warehouse_code');
          $table->integer('product_counts');
          $table->timestamps();

          $table->foreign('product_id')->references('id')->on('product_information')->onDelete('cascade');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
