<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('option_types', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('name');

            $table->integer('product_id')->unsigned();
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->softDeletes();

        });
    }



    public function down()
    {
        Schema::dropIfExists('option_types');
    }
};
