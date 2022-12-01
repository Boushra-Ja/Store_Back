<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->date('delivery_time');
            $table->integer('delivery_price');
            $table->integer('customer_id')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->integer('store_id')->unsigned();
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            $table->integer('status_id')->unsigned();
            $table->foreign('status_id')->references('id')->on('order_statuses')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
