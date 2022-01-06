<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomerPersonelIdToBriefs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('briefs', function (Blueprint $table) {
            Schema::table('briefs', function (Blueprint $table) {
                $table->unsignedBigInteger('customer_personel_id')->unsigned()->after('customer_id')->nullable();
                $table->foreign('customer_personel_id')->references('id')->on('customer_personels');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('briefs', function (Blueprint $table) {
            //
        });
    }
}
