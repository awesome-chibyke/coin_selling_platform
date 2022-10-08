<?php

use App\Models\PaymentModal;
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
        $PaymentModalInstance = new PaymentModal();
        Schema::create('payment_modals', function (Blueprint $table) use($PaymentModalInstance) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('user_unique_id');
            $table->string('premium_plan_id')->nullable();
            $table->string('amount_in_usd')->nullable();
            $table->string('coin')->nullable();
            $table->string('coin_value')->nullable();
            $table->string('pay_address')->nullable();
            $table->text('description')->nullable();
            $table->text('hosted_url')->nullable();
            $table->string('action_type')->nullable();
            $table->string('payment_option')->nullable();
            $table->string('status')->default($PaymentModalInstance->paymentModalPendingStatus);
            $table->string('reference')->nullable();
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
        Schema::dropIfExists('payment_modals');
    }
};