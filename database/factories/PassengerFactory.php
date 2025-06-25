<?php

namespace Database\Factories;

use App\Models\Flight;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class PassengerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $flightIds = null;
        if ($flightIds === null) {
            $flightIds = Flight::pluck('id')->toArray();
        }

        return [
            'firstName' => $this->faker->firstName(),
            'flight_id' => $this->faker->randomElement($flightIds),
            'lastName' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password123'), // hashed password
            'DOB' => $this->faker->date('Y-m-d', '2005-01-01'),
            'passport_expiry_date' => $this->faker->dateTimeBetween('now', '+10 years')->format('Y-m-d'),
        ];
    }
}
