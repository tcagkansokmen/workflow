<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContractStatusesToOffers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('contract')->nullable()->after('status');
            $table->string('contract_status')->nullable()->after('status');
        });
        Schema::table('offer_files', function (Blueprint $table) {
            $table->string('type')->nullable()->after('filename');
        });
        Schema::table('offer_comments', function (Blueprint $table) {
            $table->string('type')->nullable()->after('comment');
        });
        Schema::table('offer_messages', function (Blueprint $table) {
            $table->string('type')->nullable()->after('comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offers', function (Blueprint $table) {
            //
        });
    }
}
