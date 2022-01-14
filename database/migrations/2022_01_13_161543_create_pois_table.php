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
            $table->id();
            $table->string('poi_name')->unique();
            $table->string('street');
            $table->integer('zipcode');
            $table->string('city');
            $table->string('description');
            $table->string('open');
            $table->string('website');
            $table->string('photo');
            $table->double('long', 9,6);
            $table->double('lat', 9,6);
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
