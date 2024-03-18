<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDistributorOrderApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distributor_order_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('admin_approve_at')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamp('manager_approve_at')->nullable();
            $table->string('status')->default('Waiting Approval');
            $table->string('admin_status')->nullable();
            $table->string('manager_status')->nullable();
            $table->string('reason')->nullable();
            $table->string('distributor')->nullable();
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
        Schema::dropIfExists('distributor_order_approvals');
    }
}
