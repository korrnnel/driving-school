<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition(): array
    {
        return [
            'date' => fake()->dateTimeBetween('-2 months', 'now'), // DODANA DATA
            'type' => 'internal',
            'category' => 'theory',
            'result' => 'passed',
        ];
    }

    public function stateExam(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'state',
        ]);
    }

    public function practice(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'practice',
        ]);
    }

    public function failed(): static
    {
        return $this->state(fn (array $attributes) => [
            'result' => 'failed',
        ]);
    }
}
