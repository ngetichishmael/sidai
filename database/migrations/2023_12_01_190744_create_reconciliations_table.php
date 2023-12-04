<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReconciliationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reconciliations', function (Blueprint $table) {
           $table->id();
           $table->string('reconciliation_code');
           $table->decimal('cash', 10, 2);
           $table->decimal('bank', 10, 2);
           $table->decimal('cheque', 10, 2);
           $table->decimal('mpesa', 10, 2);
           $table->decimal('total', 10, 2);
           $table->string('status');
           $table->string('note')->nullable();
           $table->string('warehouse_code');
           $table->string('reconciled_to')->nullable();
           $table->string('sales_person')->nullable();
           $table->string('approved_by')->nullable();
           $table->timestamp('approved_on')->nullable();
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
        Schema::dropIfExists('reconciliations');
    }
}
