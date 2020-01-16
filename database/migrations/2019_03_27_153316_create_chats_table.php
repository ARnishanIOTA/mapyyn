<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('client_id')->nullable()->index();
            $table->unsignedInteger('provider_id')->nullable()->index();
            $table->unsignedInteger('offer_id')->nullable()->index();
            $table->unsignedInteger('request_offer_id')->nullable()->index();
            $table->integer('type')->comment('[1 => client_admin , 2 => client_provider, 3 => provider_admin]');
            $table->integer('offer_type')->comment('[1 => offer , 2 => request offer]');
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('provider_id')->references('id')->on('providers')->onDelete('cascade');
            $table->foreign('offer_id')->references('id')->on('offers')->onDelete('cascade');
            $table->foreign('request_offer_id')->references('id')->on('request_offers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
    }
}
