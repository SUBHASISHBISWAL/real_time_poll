<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('poll_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->string('option_text');
            $table->timestamps();
            
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('poll_options');
    }
};
