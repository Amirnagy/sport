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
        Schema::create('football_coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('country');
            $table->string('city');
            $table->integer('age');
            $table->integer('jop_title');
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('phone_number');
            $table->string('whatsapp_number');
            // $table->enum('possibility_travel',['yes','no'])->default('no');
            $table->string('cv')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('football_coaches');
    }
};
