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
        Schema::create('student_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('student_name');
            $table->string('student_school');
            $table->string('level');
            $table->unsignedInteger('age');
            $table->string('city');
            $table->string('quarter');
            $table->string('type');
            $table->text('comment')->nullable();
            $table->text('subjects')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_profiles');
    }
};
