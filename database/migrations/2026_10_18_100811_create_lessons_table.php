<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('instructor_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained('vehicles')->cascadeOnDelete();
            $table->foreignId('booked_by_id')->constrained('users')->cascadeOnDelete();
            $table->boolean('is_additional_hour')->default(false);
            $table->integer('tasks_planned_count')->default(0);
            $table->integer('tasks_completed_count')->default(0);
            $table->integer('interventions_count')->default(0);
            $table->integer('critical_mistakes_count')->default(0);
            $table->integer('minor_mistakes_count')->default(0);
            $table->text('instructor_notes')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time');
            $table->enum('status', ['scheduled', 'completed', 'no_show', 'cancelled'])->default('scheduled');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
