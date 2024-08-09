<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Flight;

class FlightFactory extends Factory
{
    protected $model = Flight::class;

    public function definition()
    {
        return [
            'name' => $this->faker->words(3, true),
            'origin' => $this->faker->city,
            'destination' => $this->faker->city,
            'departure_time' => $this->faker->dateTimeThisYear,
        ];
    }
}
