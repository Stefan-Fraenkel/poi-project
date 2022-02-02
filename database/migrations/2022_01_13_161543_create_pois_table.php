<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pois', function (Blueprint $table) {
            $table->id('poi_id');
            $table->string('poi_name')->unique();
            $table->string('street');
            $table->integer('zipcode');
            $table->string('city');
            $table->string('description')->nullable();
            $table->string('open')->nullable();
            $table->string('website')->nullable();
            $table->string('photo')->nullable();
            $table->double('long', 9,6)->nullable();
            $table->double('lat', 9,6)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pois');
    }
}
