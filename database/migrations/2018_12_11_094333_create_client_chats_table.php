<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_chats', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id')->index()->nullable();
            $table->unsignedInteger('offer_id')->index()->nullable();
            $table->unsignedInteger('request_offer_id')->index()->nullable();
            $table->string('type')->default('admin');
            $table->string('offer_type')->default('offer');
            $table->timestamps();
            
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
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
        Schema::dropIfExists('client_chats');
    }
}
