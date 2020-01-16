<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientChatMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_chat_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->text('message');
            $table->unsignedInteger('chat_id')->index()->nullable();
            $table->timestamps();

            $table->foreign('chat_id')->references('id')->on('client_chats')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('client_chat_messages');
    }
}
