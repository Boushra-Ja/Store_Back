<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->integer('order_product_id')->unsigned();
            $table->foreign('order_product_id')->references('id')->on('order_products')->onDelete('cascade');
        });
    }


    public function down()
    {
        Schema::table('product_options', function (Blueprint $table) {
            $table->dropColumn('order_product_id') ;
        });
    }
};
