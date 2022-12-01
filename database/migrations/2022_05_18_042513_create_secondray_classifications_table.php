<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('secondray_classifications', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('title');

            $table->integer('classification_id')->unsigned();
            $table->foreign('classification_id')->references('id')->on('classifications')->onDelete('cascade');
            $table->softDeletes();

        });
    }

    public function down()
    {
        Schema::dropIfExists('secondray_classifications');
    }
};
