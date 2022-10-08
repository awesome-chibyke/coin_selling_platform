<?php

use App\Models\User;
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
        $userModel = new User();
        Schema::create('users', function (Blueprint $table) use($userModel){
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('username')->nullable();
            $table->string('referrer_username')->nullable();
            $table->string('email_subcription')->default('yes');
            $table->string('type_of_user')->default($userModel->normalUserType);
            $table->softDeletes('deleted_at', 6);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};