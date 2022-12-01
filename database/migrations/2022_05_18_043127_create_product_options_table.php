<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('product_options', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->integer('option_values_id')->unsigned();
            $table->foreign('option_values_id')->references('id')->on('optioin_values')->onDelete('cascade');
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('product_options');
    }
};
