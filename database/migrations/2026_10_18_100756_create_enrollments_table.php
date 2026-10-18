<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->string('pkk_number', 20)->nullable();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->enum('status', ['in_progress', 'completed', 'dropped', 'cancelled'])->default('in_progress');
            $table->timestamp('theory_completed_at')->nullable();
            $table->timestamp('practice_completed_at')->nullable();
            $table->timestamp('enrolled_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
