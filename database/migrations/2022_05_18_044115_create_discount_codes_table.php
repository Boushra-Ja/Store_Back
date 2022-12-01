<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->increments('id');
           // $table->string('its_for');
            $table->string('condition')->nullable();
            $table->integer('condition_value')->nullable();
            $table->string('discount_code');
            $table->integer('discounts_id')->unsigned();
            $table->foreign('discounts_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('discount_codes');
    }
};
