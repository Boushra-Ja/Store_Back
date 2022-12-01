<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->increments('id');
            $table->String('image')->nullable();
            $table->String('discription')->nullable();
            $table->String('title');
            $table->integer('store_id')->unsigned();
            $table->timestamps();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('collections');
    }
};
