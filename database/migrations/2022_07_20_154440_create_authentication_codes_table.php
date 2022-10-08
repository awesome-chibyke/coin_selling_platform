<?php

use App\Traits\AuthenticationTrait;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    use AuthenticationTrait;
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $unUsedCodeStatus = $this->unUsedCodeStatus;
        Schema::create('authentication_codes', function (Blueprint $table) use($unUsedCodeStatus){
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('code');
            $table->string('type');
            $table->string('user_unique_id');
            $table->string('status')->default($unUsedCodeStatus);
            $table->dateTime('expiration_time')->nullable();
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
        Schema::dropIfExists('authentication_codes');
    }
};
