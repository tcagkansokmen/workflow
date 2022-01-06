<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            $table->unsignedBigInteger('assemby_id')->unsigned()->nullable()->after('bill_id');
            $table->foreign('assemby_id')->references('id')->on('assemblies');

            $table->unsignedBigInteger('production_id')->unsigned()->nullable()->after('bill_id');
            $table->foreign('production_id')->references('id')->on('productions');

            $table->unsignedBigInteger('printing_id')->unsigned()->nullable()->after('bill_id');
            $table->foreign('printing_id')->references('id')->on('printings');

            $table->unsignedBigInteger('exploration_id')->unsigned()->nullable()->after('bill_id');
            $table->foreign('exploration_id')->references('id')->on('explorations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
        });
    }
}
