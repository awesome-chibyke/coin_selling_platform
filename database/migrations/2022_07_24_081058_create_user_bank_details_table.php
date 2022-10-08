<?php

use App\Models\UserBankDetails;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $UserBankDetails = new UserBankDetails();
        Schema::create('user_bank_details', function (Blueprint $table)  use($UserBankDetails) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('user_unique_id');
            $table->string('account_number');
            $table->string('bank_code');
            $table->string('beneficiary_name');
            $table->string('bank_name')->nullable();
            $table->string('beneficiary_id')->nullable();
            $table->string('status')->default($UserBankDetails->activeStatus);
            $table->softDeletes();
            $table->timestamps();
        });////id, unique_id, user_unique_id, account_number, bank_code, beneficiary_name, status (active, not-active)
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_bank_details');
    }
};