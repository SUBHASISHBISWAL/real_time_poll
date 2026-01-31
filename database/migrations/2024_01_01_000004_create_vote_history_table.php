<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vote_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('option_id');
            $table->string('ip_address', 45);
            $table->enum('action', ['voted', 'changed', 'released']);
            $table->unsignedBigInteger('previous_option_id')->nullable();
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('poll_options')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('vote_history');
    }
};
