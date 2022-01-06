<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_group_id')->nullable();
            $table->foreign('user_group_id')
                ->references('id')->on('user_groups')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('user_groups')
                ->onDelete('cascade');
            $table->string('key');
            $table->string('sef')->nullable();
            $table->string('title')->nullable();
            $table->string('color')->nullable();
            $table->integer('priority')->default(1);
            $table->string('type')->default('next'); //decline,accept,revise
            $table->string('next')->nullable();
            $table->integer('is_editable')->default(1);
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
        Schema::dropIfExists('workflows');
    }
}
