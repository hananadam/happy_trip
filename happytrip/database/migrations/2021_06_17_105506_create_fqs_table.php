<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFqsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('topic_id')->unsigned();
            $table->string('question');
            $table->text('answer');
            $table->timestamps();
        });
//        Schema::table('fqs', function($table) {
//            $table->foreign('topic_id')->references('id')->on('fqs_topics')->onDelete('cascade');
//        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fqs');
    }
}
