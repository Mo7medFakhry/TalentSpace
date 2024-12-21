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
        Schema::create('file_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('talent_id');
            $table->string('title');
            $table->text('description');
            $table->longText('video_base64');
            $table->string('tags')->nullable();
            $table->enum('Status', ['approved', 'pending', 'rejected']);
            $table->foreign('talent_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_file_media');
    }
};
