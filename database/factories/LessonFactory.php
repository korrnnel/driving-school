<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $planned = fake()->numberBetween(5, 10);

        return [
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => 'completed',
            'is_additional_hour' => false,
            'tasks_planned_count' => $planned,
            // Przeciętna jazda: zrobiono od 70% do wszystkich zadań
            'tasks_completed_count' => fake()->numberBetween((int)ceil($planned * 0.7), $planned),
            'interventions_count' => 0,
            'critical_mistakes_count' => fake()->numberBetween(0, 2),
            'minor_mistakes_count' => fake()->numberBetween(1, 5),
            'instructor_notes' => fake()->boolean(30) ? fake()->sentence() : null,
        ];
    }

    // Jazda prymusa (idealna)
    public function perfectRide(): static
    {
        return $this->state(function (array $attributes) {
            $planned = fake()->numberBetween(5, 12);
            return [
                'tasks_planned_count' => $planned,
                'tasks_completed_count' => $planned, // zrobione wszystko
                'interventions_count' => 0,
                'critical_mistakes_count' => 0,
                'minor_mistakes_count' => fake()->numberBetween(0, 2),
            ];
        });
    }

    // Jazda tragiczna (dużo błędów, niska realizacja)
    public function terribleRide(): static
    {
        return $this->state(function (array $attributes) {
            $planned = fake()->numberBetween(4, 7);
            return [
                'tasks_planned_count' => $planned,
                'tasks_completed_count' => fake()->numberBetween((int)ceil($planned * 0.5), (int)ceil($planned * 0.7)),
                'interventions_count' => 1,
                'critical_mistakes_count' => fake()->numberBetween(0, 1),
                'minor_mistakes_count' => fake()->numberBetween(2, 3),
                'instructor_notes' => 'Kursant stwarza ogromne zagrożenie. Wymaga powrotu do podstaw.',
            ];
        });
    }

    // Jazda z pustymi zadaniami (np. luźna jazda po mieście, z czym musi sobie poradzić Twój wzór)
    public function noTasksRide(): static
    {
        return $this->state(fn (array $attributes) => [
            'tasks_planned_count' => 0,
            'tasks_completed_count' => 0,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'tasks_planned_count' => 0,
            'tasks_completed_count' => 0,
            'interventions_count' => 0,
            'critical_mistakes_count' => 0,
            'minor_mistakes_count' => 0,
        ]);
    }
}
