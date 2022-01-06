<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExplorationDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exploration_designs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exploration_id')->unsigned()->nullable();
            $table->foreign('exploration_id')->references('id')->on('explorations');
            $table->unsignedBigInteger('designer_id')->unsigned()->nullable();
            $table->foreign('designer_id')->references('id')->on('users');
            $table->text('comment')->nullable();
            $table->integer('is_active')->nullable();
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
        Schema::dropIfExists('exploration_designs');
    }
}
