<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->String('image');
            $table->String('discription');
            $table->boolean('gift');
            $table->String('name');
            $table->integer('prepration_time');
            $table->String('party')->nullable();
            $table->string('age');
            $table->bigInteger('selling_price');
            $table->bigInteger('cost_price');
            $table->integer('number_of_sales');
            $table->boolean('return_or_replace');
            $table->integer('collection_id')->unsigned();
            $table->foreign('collection_id')->references('id')->on('collections')->onDelete('cascade');
            $table->softDeletes();

        });
    }



    public function down()
    {
        Schema::dropIfExists('products');
    }
};
