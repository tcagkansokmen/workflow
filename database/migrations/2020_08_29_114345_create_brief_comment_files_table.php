<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBriefCommentFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brief_comment_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brief_comment_id')->unsigned()->nullable();
            $table->foreign('brief_comment_id')->references('id')->on('brief_comments');
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
        Schema::dropIfExists('brief_comment_files');
    }
}
