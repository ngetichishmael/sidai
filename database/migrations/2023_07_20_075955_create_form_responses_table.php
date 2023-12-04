<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_responses', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('customer_id')->nullable();
            $table->string('region_or_route')->nullable();
            $table->string('time_period')->nullable();
            $table->string('image')->nullable();
            $table->string('project_name')->nullable();
            $table->text('feedback_comments')->nullable();

            // Presence
            $table->json('products_available')->nullable();
            $table->json('out_of_stock_prods')->nullable();
            $table->string('interested_in_new_order')->nullable();
            $table->string('stock_replenishment')->nullable();
            $table->date('expiry_date_update')->nullable();

            // Pricing
            $table->string('pricing_accuracy')->nullable();
            $table->string('incorrect_pricing_product_name')->nullable();
            $table->string('incorrect_pricing_current_price')->nullable();

            // Promotion
            $table->string('progress_status')->nullable();
            $table->text('new_insights')->nullable();

            // Placement
            $table->string('product_visible')->nullable();

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
        Schema::dropIfExists('form_responses');
    }
}
