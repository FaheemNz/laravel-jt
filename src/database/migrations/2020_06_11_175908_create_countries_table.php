<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_code');
            $table->string('phone_code');
            $table->string('flag_url');
            $table->timestamps();

//            $table->string('iso3', 3)->nullable();
//            $table->string('iso2', 2)->nullable();
//            $table->string('phonecode')->nullable();
//            $table->string('capital')->nullable();
//            $table->string('currency')->nullable();
//            $table->string('native')->nullable();
//
//            $table->string('emoji')->nullable();
//            $table->string('emojiU')->nullable();
//
//            $table->tinyInteger('flag')->default(1);
//            $table->string('wikiDataId')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Schema::dropIfExists('countries');
    }
}
