<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePreviledgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previledges', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('type_of_user_id');
            $table->string('role_id');
            $table->softDeletes('deleted_at', 6);
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
        Schema::dropIfExists('previledges');
    }
}
