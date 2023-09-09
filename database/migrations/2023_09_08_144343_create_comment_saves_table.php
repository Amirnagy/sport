<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comment_saves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('reel_id')->references('id')->on('reels')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('comment_id')->references('id')->on('comments')->cascadeOnDelete()->cascadeOnUpdate();
            $table->unique(['user_id','reel_id','comment_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comment_saves');
    }
};
