<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBillProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assembly_id')->nullable();
            $table->foreign('assembly_id')
                ->references('id')->on('assemblies')
                ->onDelete('cascade');
            $table->unsignedBigInteger('printing_id')->nullable();
            $table->foreign('printing_id')
                ->references('id')->on('printings')
                ->onDelete('cascade');
            $table->unsignedBigInteger('production_id')->nullable();
            $table->foreign('production_id')
                ->references('id')->on('productions')
                ->onDelete('cascade');
            $table->unsignedBigInteger('bill_id')->nullable();
            $table->foreign('bill_id')
                ->references('id')->on('bills')
                ->onDelete('cascade');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users')
                ->onDelete('cascade');
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
        Schema::dropIfExists('bill_projects');
    }
}
