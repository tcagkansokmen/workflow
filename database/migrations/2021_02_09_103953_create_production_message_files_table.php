<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionMessageFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('production_message_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_message_id');
            $table->foreign('production_message_id')
                ->references('id')->on('production_messages')
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
        Schema::dropIfExists('production_message_files');
    }
}
