<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVenueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('venue', function (Blueprint $table) {
            $table->integer('id', true);
            $table->string('uuid', 36);
            $table->string('name', 245);
            $table->string('address', 254);
            $table->integer('phone');
            $table->integer('city_id')->index('city_id');
            $table->double('latitud');
            $table->double('longitud');
            $table->boolean('status')->nullable()->default(true);
            $table->dateTime('created_at');
            $table->dateTime('update_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('venue');
    }
}
