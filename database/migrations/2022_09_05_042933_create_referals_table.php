<?php

use App\Models\Referal;
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
        $ReferalModelInstance = new Referal();
        Schema::create('referals', function (Blueprint $table) use($ReferalModelInstance) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('reffered_unique_id');
            $table->string('refferer_unique_id');
            $table->string('payment_unique_id');
            $table->string('status')->default($ReferalModelInstance->pendingStatus);
            $table->decimal('amount', 13, 4);
            $table->softDeletes();
            $table->timestamps();
        });//unique_id, reffered_unique_id, refferer_unique_id, payment_unique_id, amount, status (pending, processing-transfer, payed)
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referals');
    }
};
