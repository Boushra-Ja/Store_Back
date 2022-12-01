<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->String('title');
            $table->softDeletes();

        });
    }


    public function down()
    {
        Schema::dropIfExists('classifications');
    }
};
