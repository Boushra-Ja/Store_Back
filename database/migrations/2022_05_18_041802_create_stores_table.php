<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->increments('id');
            $table->String('name');
            $table->String('image')->nullable();
            $table->String('Brand')->nullable();
            $table->String('discription');
            $table->String('facebook')->nullable();
            $table->integer('num_of_salling')->nullable();
            $table->String('status')->nullable();
            $table->bigInteger('mobile')->nullable();
            $table->string('delivery_area');
            $table->timestamps();
            $table->softDeletes();




        });
    }



    public function down()
    {
        Schema::dropIfExists('stores');
    }
};
