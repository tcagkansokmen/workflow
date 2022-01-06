<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfferMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('offer_message_id')->unsigned()->nullable();
            $table->foreign('offer_message_id')->references('id')->on('offer_messages');
            $table->string('filename');
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
        Schema::dropIfExists('offer_message_files');
    }
}
