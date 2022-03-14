<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('reference_code');
            $table->string('transaction_date');
            $table->date('check_in');
            $table->date('check_out');
            $table->string('hotel');
            $table->integer('room_code');
            $table->integer('room_price');
            $table->boolean('voucher')->default(0);
            $table->boolean('loyality')->default(0);
            $table->integer('nights');
            $table->string('cancellation_policy')->nullable();
            $table->float('subtotal', 8, 2);
            $table->string('vat');
            $table->float('total', 8, 2);
            $table->string('remark');
            $table->timestamps();

            //$table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bookings');
    }
}
