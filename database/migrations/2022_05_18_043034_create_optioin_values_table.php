<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('optioin_values', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('value');
            $table->integer('option_type_id')->unsigned();
            $table->foreign('option_type_id')->references('id')->on('option_types')->onDelete('cascade');
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('optioin_values');
    }
};
