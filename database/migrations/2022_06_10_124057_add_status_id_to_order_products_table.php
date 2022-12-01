<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('order_statuses')->onDelete('cascade');
            $table->integer('discount_products_id')->unsigned();
            $table->foreign('discount_products_id')->references('id')->on('discount_products')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('order_products', function (Blueprint $table) {
            $table->dropColumn('status_id');
            $table->dropColumn('discount_products_id');
        });
    }
};
