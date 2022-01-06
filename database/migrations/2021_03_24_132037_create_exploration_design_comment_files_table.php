<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExplorationDesignCommentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exploration_design_comment_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('exp_design_comment_id')->unsigned()->nullable();
            $table->foreign('exp_design_comment_id')->references('id')->on('exploration_design_comments');
            $table->string('file')->nullable();
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
        Schema::dropIfExists('exploration_design_comment_files');
    }
}
