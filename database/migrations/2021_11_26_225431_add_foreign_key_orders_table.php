<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('payment_type_id')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('discount_id')->references('id')->on('discounts')->onUpdate('cascade')->onDelete('SET NULL');
            $table->foreign('status_id')->references('id')->on('order_statuses')->onUpdate('cascade')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
}
