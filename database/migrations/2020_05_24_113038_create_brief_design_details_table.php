<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBriefDesignDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brief_design_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brief_id')->unsigned()->nullable();
            $table->foreign('brief_id')->references('id')->on('briefs');
            $table->unsignedBigInteger('design_id')->unsigned()->nullable();
            $table->foreign('design_id')->references('id')->on('brief_designs');
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
        Schema::dropIfExists('brief_design_details');
    }
}
