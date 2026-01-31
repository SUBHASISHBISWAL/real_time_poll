<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('option_id');
            $table->string('ip_address', 45); // IPv6 support
            $table->timestamp('voted_at')->useCurrent();
            
            // Index for quick duplicate checking
            $table->index(['poll_id', 'ip_address']);
            
            $table->foreign('poll_id')->references('id')->on('polls')->onDelete('cascade');
            $table->foreign('option_id')->references('id')->on('poll_options')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('votes');
    }
};
