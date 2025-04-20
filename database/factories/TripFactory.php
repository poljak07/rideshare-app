<?php

namespace Database\Factories;

use App\Models\Car;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Trip>
 */
class TripFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'car_id' => Car::inRandomOrder()->first()->id,
            'is_started' => $this->faker->boolean,
            'is_complete' => $this->faker->boolean,
            'origin' => $this->faker->city,
            'price' => $this->faker->numberBetween(5, 100),
            'destination' => json_encode([
                'lat' => $this->faker->latitude(),
                'lng' => $this->faker->longitude()
            ]),
            'destination_name' => $this->faker->city,
            'departure_time' => now()
                ->addDays($this->faker->numberBetween(1, 10))
                ->setMinutes(floor(now()->minute / 5) * 5)
                ->setSeconds(0),
            'driver_location' => json_encode([
                'lat' => $this->faker->latitude(43.85, 44.05),
                'lng' => $this->faker->longitude(18.30, 18.45),
                'updated_at' => now(),
                ]),
        ];
    }
}
