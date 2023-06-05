<?php

use App\Models\products\product_information;
use App\Models\StockRequisition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequisitionProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requisition_products', function (Blueprint $table) {
            $table->id();
            $table->integer('product_id');
            $table->integer('requisition_id');
            $table->integer('quantity');
            $table->foreignIdFor(StockRequisition::class);
            $table->foreignIdFor(product_information::class);
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
        Schema::dropIfExists('requisition_products');
    }
}
