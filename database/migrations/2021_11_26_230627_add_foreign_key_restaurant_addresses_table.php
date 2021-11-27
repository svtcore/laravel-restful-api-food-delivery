<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyRestaurantAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('restaurant_addresses', function (Blueprint $table) {
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('restaurant_cities')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('street_type_id')->references('id')->on('restaurant_street_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurant_addresses', function (Blueprint $table) {
            //
        });
    }
}
