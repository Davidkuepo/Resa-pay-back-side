<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('level')->nullable();
            $table->string('subject')->nullable();
            $table->string('location')->nullable();
            $table->enum('mode', ['online', 'offline'])->default('offline');
            $table->integer('price')->nullable();
            $table->unsignedBigInteger('tutor_id')->nullable();
            $table->foreign('tutor_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });

        Schema::create('course_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            $table->string('file_path');
            $table->string('type');
            $table->string('title')->nullable();
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_documents');
        Schema::dropIfExists('courses');
    }
};
