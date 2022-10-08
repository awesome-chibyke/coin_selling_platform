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
        Schema::create('coin_conversion_rates', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('rate_in_local_currency');
            $table->string('local_currency')->default('NGN');
            $table->string('equi_in_dollar');
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
        Schema::dropIfExists('coin_conversion_rates');
    }
};