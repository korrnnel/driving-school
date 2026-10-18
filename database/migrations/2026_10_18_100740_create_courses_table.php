<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->enum('category', ['A', 'B', 'C']);
            $table->string('name');
            $table->enum('type', ['standard', 'accelerated', 'weekend'])->default('standard');
            $table->date('start_date')->nullable();
            $table->integer('required_theory_hours');
            $table->integer('required_practice_hours');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
