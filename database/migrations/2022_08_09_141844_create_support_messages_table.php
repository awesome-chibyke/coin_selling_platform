<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('support_messages', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('support_unique_id');
            $table->longText('message');
            $table->string('sender_id');
            $table->string('reciever_id');
            $table->softDeletes();
            $table->timestamps();//unique_id support_unique_id, message, sender_id, reciever_id,
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_messages');
    }
};