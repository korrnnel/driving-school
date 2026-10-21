<?php



namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;



/**

 * @extends Factory<Vehicle>

 */

class VehicleFactory extends Factory

{
    protected $model = Vehicle::class;

    public function definition(): array

    {
        $cars = [
            ['brand' => 'Toyota', 'model' => 'Yaris'],
            ['brand' => 'Skoda', 'model' => 'Fabia'],
            ['brand' => 'Hyundai', 'model' => 'i20'],
            ['brand' => 'Kia', 'model' => 'Rio'],

        ];

        $selected = fake()->randomElement($cars);

        return [

            'category' => 'B',
            'brand' => $selected['brand'],
            'model' => $selected['model'],
            'registration_number' => fake()->unique()->regexify('[A-Z]{2,3} [0-9]{5}'),
            'status' => 'active',

        ];

    }
    public function categoryA(): static

    {
        return $this->state(function (array $attributes) {

            $motorcycles = [
                ['brand' => 'Yamaha', 'model' => 'MT-07'],
                ['brand' => 'Honda', 'model' => 'CB500F'],
                ['brand' => 'Kawasaki', 'model' => 'Z650'],

            ];

            $selected = fake()->randomElement($motorcycles);

            return [
                'category' => 'A',
                'brand' => $selected['brand'],
                'model' => $selected['model'],
                'registration_number' => fake()->unique()->regexify('[A-Z]{2,3} [0-9]{4}'),

            ];

        });

    }

    public function categoryC(): static

    {
        return $this->state(function (array $attributes) {

            $trucks = [
                ['brand' => 'MAN', 'model' => 'TGL'],
                ['brand' => 'Mercedes-Benz', 'model' => 'Atego'],
                ['brand' => 'DAF', 'model' => 'LF'],

            ];

            $selected = fake()->randomElement($trucks);

            return [
                'category' => 'C',
                'brand' => $selected['brand'],
                'model' => $selected['model'],

            ];

        });

    }

    public function maintenance(): static

    {
        return $this->state(fn (array $attributes) => [
            'status' => 'maintenance',

        ]);
    }



    public function outOfService(): static

    {
        return $this->state(fn (array $attributes) => [
            'status' => 'out_of_service',

        ]);
    }
}
