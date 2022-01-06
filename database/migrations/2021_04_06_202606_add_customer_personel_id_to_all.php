<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerPersonelIdToAll extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assemblies', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
        Schema::table('printings', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
        Schema::table('productions', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
        Schema::table('explorations', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
        Schema::table('bills', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
        Schema::table('offers', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
        Schema::table('projects', function (Blueprint $table) {
            $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
            $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('all', function (Blueprint $table) {
            //
        });
    }
}
