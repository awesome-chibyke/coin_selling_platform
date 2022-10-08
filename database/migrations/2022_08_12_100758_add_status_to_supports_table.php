<?php

use App\Models\Support\Support;
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
        $supportModelInstance = new Support();
        Schema::table('supports', function (Blueprint $table) use($supportModelInstance) {
            $table->string('status')->default($supportModelInstance->supportMessageOpenStatus);
            $table->string('read_status')->default($supportModelInstance->supportMessageUnreadStatus);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('supports', function (Blueprint $table) {
            //
        });
    }
};
