<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->string('category_type', 50);
            $table->string('travel_type', 50);
            $table->unsignedInteger('category_id')->index()->nullable();
            $table->unsignedInteger('provider_id')->index()->nullable();
            $table->unsignedInteger('client_id')->index()->nullable();
            $table->integer('adult')->default(1);
            $table->integer('children')->default(1);
            $table->integer('babies')->default(1);
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('from_country_ar');
            $table->string('from_country_en');
            $table->string('from_city_ar');
            $table->string('from_city_en');
            $table->string('to_country_ar');
            $table->string('to_country_en');
            $table->string('to_city_ar');
            $table->string('to_city_en');
            $table->string('from_date');
            $table->string('to_date');
            $table->string('from_price');
            $table->string('from_price');
            $table->string('to_price');
            $table->string('final_price')->naullable();
            $table->string('hotel_rate');
            $table->string('note', 255);
            $table->integer('recive_days')->default(1);
            $table->string('status')->default('new');
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offers');
    }
}
