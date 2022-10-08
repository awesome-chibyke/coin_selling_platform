<?php

use App\Models\BulkMail;
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
        $bulkMailModelInstance = new BulkMail();
        Schema::create('bulk_mails', function (Blueprint $table) use($bulkMailModelInstance) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->text('title')->nullable();
            $table->longText('mail_body')->nullable();
            $table->string('mail_readers')->default($bulkMailModelInstance->sendToAllUsers);
            $table->string('read_status')->default($bulkMailModelInstance->unReadStatus);
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
        Schema::dropIfExists('bulk_mails');
    }
};