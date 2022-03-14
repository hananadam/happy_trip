<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('phone',100);
            $table->string('email',100);
            $table->string('logo',250);
            $table->string('address',250);
            $table->text('about')->nullable();
            $table->text('policy')->nullable();
            $table->text('terms')->nullable();
            $table->string('facebook',250)->nullable();
            $table->string('twitter',250)->nullable();
            $table->string('instagram',250)->nullable();
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
        Schema::dropIfExists('settings');
    }
}
