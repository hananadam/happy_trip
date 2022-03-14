<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCoulmnsToRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ratings', function (Blueprint $table) {
//            $table->unsignedBigInteger('hotel_id');
//            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
//            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
//             $table->unsignedBigInteger('code');
        });
    }
}
