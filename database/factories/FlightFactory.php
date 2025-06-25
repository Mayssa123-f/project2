<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flight>
 */
class FlightFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         $departure = $this->faker->dateTimeBetween('now', '+1 week');
    $arrival = (clone $departure)->modify('+'.rand(1, 10).' hours');

    return [
        'number' => $this->faker->numberBetween(0,1000),
        'departure_city' => $this->faker->city(),
        'arrival_city' => $this->faker->city(),
        'departure_time' => $departure,
        'arrival_time' => $arrival,
    ];
    }
}
