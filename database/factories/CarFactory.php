<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'year' => $this->faker->numberBetween(2000, 2024),
            'model' => $this->faker->randomElement(
                ['Golf VII', 'BMW 5 Series', 'Toyota Corolla', 'Honda Civic', 'BMW X5', 'Audi A4', 'Mercedes C-Class']
            ),
            'color' => $this->faker->safeColorName(),
            'license_plate' => strtoupper($this->faker->bothify('??-####')),
        ];
    }
}
