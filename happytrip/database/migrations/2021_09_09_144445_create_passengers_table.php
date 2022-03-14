<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassengersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('passengers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('category'); 
            $table->string('title')->nullable(); 
            $table->string('name'); 
            $table->string('surname'); 
            $table->string('email')->nullable(); 
            $table->string('nationality');
            $table->date('birthday');
            $table->string('phone')->nullable(); 
            $table->string('cardno');
            $table->date('expiration');
            $table->timestamps();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('passengers');
    }
}


      