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
        Schema::create('privilladge_helpers', function (Blueprint $table) {
            $table->id();

            $table->integer('helper_id')->unsigned();
            $table->foreign('helper_id')->references('id')->on('helpers')->onDelete('cascade');

            $table->integer('privilladge_id')->unsigned();
            $table->foreign('privilladge_id')->references('id')->on('privilladges')->onDelete('cascade');

            $table->softDeletes();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('privilladge_helpers');
    }
};
