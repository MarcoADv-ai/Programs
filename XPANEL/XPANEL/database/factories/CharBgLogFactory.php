<?php

namespace Database\Factories;

use App\Models\CharBgLog;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharBgLogFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CharBgLog::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'time' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'killer' => $this->faker->numberBetween(100000, 999999),
            'killer_id' => $this->faker->numberBetween(100000, 999999),
            'killed' => $this->faker->numberBetween(100000, 999999),
            'killed_id' => $this->faker->numberBetween(100000, 999999),
            'map' => 'prontera',
            'skill' => $this->faker->numberBetween(0, 99999),
        ];
    }
}
