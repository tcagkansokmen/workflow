<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('brand');
            $table->string('model');
            $table->string('plate');
            $table->string('type');
            $table->string('is_loan')->default(0);
            $table->string('loan_end')->nullable();
            $table->date('kasko_start')->nullable();
            $table->date('kasko_end')->nullable();
            $table->date('insurance_start')->nullable();
            $table->date('insurance_end')->nullable();
            $table->date('care_date')->nullable();
            $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('vehicles');
    }
}
