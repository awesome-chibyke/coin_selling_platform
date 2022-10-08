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
        Schema::create('support_files', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->string('support_message_unique_id');
            $table->string('filename');
            $table->softDeletes();
            $table->timestamps();
        });
    }//support_files: unique_id, support_message_unique_id, filename

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('support_files');
    }
};
