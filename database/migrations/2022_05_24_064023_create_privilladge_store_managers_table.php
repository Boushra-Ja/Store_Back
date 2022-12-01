<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privilladge_store_managers', function (Blueprint $table) {
            $table->id();
            $table->integer('store_manager_id')->unsigned();
            $table->foreign('store_manager_id')->references('id')->on('store_managers')->onDelete('cascade');

            $table->integer('privilladge_id')->unsigned();
            $table->foreign('privilladge_id')->references('id')->on('privilladges')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilladge_store_managers');
    }
};
