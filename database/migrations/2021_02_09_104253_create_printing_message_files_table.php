<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintingMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('printing_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('printing_message_id');
            $table->foreign('printing_message_id')
                ->references('id')->on('printing_messages')
                ->onDelete('cascade');
            $table->text('filename');
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
        Schema::dropIfExists('printing_message_files');
    }
}
