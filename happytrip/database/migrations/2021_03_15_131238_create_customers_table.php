<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->string('email')->unique();
            $table->string('title',10);
            $table->string('name');
            $table->string('gender');
            $table->integer('num_adults')->default('1');
            $table->integer('num_children')->default('0');
            $table->string('photo');
            $table->string('country');
            $table->string('address');
            $table->string('dailing_code',50);
            $table->integer('mobile_num');
            $table->string('nationality',100);
            $table->integer('id_number');
            $table->string('id_expiry_date',200);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
