<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExplorationDesignDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exploration_design_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exploration_id')->unsigned()->nullable();
            $table->foreign('exploration_id')->references('id')->on('explorations');
            $table->unsignedBigInteger('design_id')->unsigned()->nullable();
            $table->foreign('design_id')->references('id')->on('exploration_designs');
            $table->string('file');
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
        Schema::dropIfExists('exploration_design_details');
    }
}
