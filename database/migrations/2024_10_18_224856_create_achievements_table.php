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
            $table->unsignedBigInteger('talent_id');
            $table->unsignedBigInteger('mentor_id');
            $table->longText('certific_base64')->nullable();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('rating');
            $table->enum('decision', ['approved', 'pending', 'rejected']);
            $table->foreign('talent_id')->references('id')->on('users');
            $table->foreign('mentor_id')->references('id')->on('users');
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
