<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'category' => fake()->randomElement(['A', 'B', 'C']),
            'type' => fake()->randomElement(['standard', 'accelerated', 'weekend']),
            'name' => function (array $attributes) {
                $typeLabels = [
                    'standard' => 'Standardowy',
                    'accelerated' => 'Przyspieszony',
                    'weekend' => 'Weekendowy',
                ];

                $typeName = $typeLabels[$attributes['type']] ?? $attributes['type'];

                return "Kurs Kat. {$attributes['category']} ({$typeName})";
            },
            'start_date' => fake()->dateTimeBetween('-4 months', '-1 month')->format('Y-m-d'),
            'required_theory_hours' => fn (array $attributes) => $attributes['category'] === 'C' ? 20 : 30,
            'required_practice_hours' => fn (array $attributes) => $attributes['category'] === 'A' ? 20 : 30,
        ];
    }

    public function categoryA(): static
    {
        return $this->state(fn (array $attributes) => ['category' => 'A']);
    }

    public function categoryB(): static
    {
        return $this->state(fn (array $attributes) => ['category' => 'B']);
    }

    public function categoryC(): static
    {
        return $this->state(fn (array $attributes) => ['category' => 'C']);
    }

    public function standard(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'standard']);
    }

    public function accelerated(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'accelerated']);
    }

    public function weekend(): static
    {
        return $this->state(fn (array $attributes) => ['type' => 'weekend']);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => fake()->dateTimeBetween('+3 days', '+2 months')->format('Y-m-d'),
        ]);
    }

    public function unscheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'start_date' => null,
        ]);
    }
}
