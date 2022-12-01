<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('raises', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('price');
            $table->String('date');
            $table->boolean('status');
            $table->integer('product_id')->unsigned();
            $table->timestamps();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('raises');
    }
};
