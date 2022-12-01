<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('discount_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('apply_to');
            $table->timestamps();
            $table->integer('discounts_id')->unsigned();
            $table->foreign('discounts_id')->references('id')->on('discounts')->onDelete('cascade');
            $table->softDeletes();

        });
    }
    public function down()
    {
        Schema::dropIfExists('discount_products');
    }
};
