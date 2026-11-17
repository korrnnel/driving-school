<?php

namespace Database\Factories;

use App\Models\InstructorAvailability;
use Illuminate\Database\Eloquent\Factories\Factory;

class InstructorAvailabilityFactory extends Factory
{
    protected $model = InstructorAvailability::class;

    public function definition(): array
    {
        return [

            'date' => fake()->dateTimeBetween('now', '+1 month'),
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ];
    }
}
