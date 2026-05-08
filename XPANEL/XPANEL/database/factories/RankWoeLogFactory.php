<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RankWoeLogFactory extends Factory
{
    private static $char_id = 150000;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time' => $this->faker->dateTime(),
            'killer' => $this->faker->name(),
            'killer_id' => $this->faker->numberBetween('150000','150030'),
            'killed' => $this->faker->name(),
            'killed_id' => $this->faker->randomNumber(),
            'map' => $this->faker->country(),
            'skill' => $this->faker->name(),
        ];
    }
}
