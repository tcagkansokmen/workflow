<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExplorationMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exploration_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exploration_message_id');
            $table->foreign('exploration_message_id')
                ->references('id')->on('exploration_messages')
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
        Schema::dropIfExists('exploration_message_files');
    }
}
