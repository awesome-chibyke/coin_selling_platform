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
        Schema::create('display_banners', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('status');
            $table->string('filename');
            $table->softDeletes();
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
        Schema::dropIfExists('display_banners');
    }
};