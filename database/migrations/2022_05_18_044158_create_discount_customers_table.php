<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discount_customers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('discount_codes_id')->unsigned();
            $table->foreign('discount_codes_id')->references('id')->on('discount_codes')->onDelete('cascade');
            $table->integer('customers_id')->unsigned();
            $table->foreign('customers_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('discount_customers');
    }
};
