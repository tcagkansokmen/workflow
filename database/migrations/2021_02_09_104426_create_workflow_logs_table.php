<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkflowLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflow_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('production_id')->nullable();
            $table->foreign('production_id')
                ->references('id')->on('productions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('assembly_id')->nullable();
            $table->foreign('assembly_id')
                ->references('id')->on('assemblies')
                ->onDelete('cascade');
            $table->unsignedBigInteger('printing_id')->nullable();
            $table->foreign('printing_id')
                ->references('id')->on('printings')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
            $table->string('status')->nullable();
            $table->string('message')->nullable();
            $table->string('title')->nullable();
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
        Schema::dropIfExists('workflow_logs');
    }
}
