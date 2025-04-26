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
        Schema::create('achievements', function (Blueprint $table) {
            $table->id();
            $table->longText('certification')->nullable();
            $table->string('Type');
            $table->text('reviewMentor')->nullable();
            $table->enum('decision', ['approved', 'pending', 'rejected']);
            $table->foreignId('talent_id')->references('id')->on('users');
            $table->foreignId('mentor_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achievements');
    }
};
