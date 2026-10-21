<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'surname' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->numerify('#########'),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'student',
            'license_number' => null,
            'assigned_vehicle_id' => null,
        ];
    }


    public function instructor(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'instructor',
            'license_number' => strtoupper(fake()->unique()->bothify('??#####')),
        ]);
    }


    public function withVehicle(?int $vehicleId = null): static
    {
        return $this->state(fn (array $attributes) => [
            'assigned_vehicle_id' => $vehicleId ?? Vehicle::factory(),
        ]);
    }


    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
