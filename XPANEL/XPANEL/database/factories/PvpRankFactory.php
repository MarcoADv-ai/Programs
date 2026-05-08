<?php

namespace Database\Factories;

use App\Models\PvpRank;
use Illuminate\Database\Eloquent\Factories\Factory;

class PvpRankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PvpRank::class;

    private static $char_id = 150000;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'char_id' => self::$char_id++,
            'kill' => $this->faker->numberBetween(0, 999999),
            'dead' => $this->faker->numberBetween(0, 999999),
            'damage_done' => $this->faker->numberBetween(0, 999999),
            'damage_received' => $this->faker->numberBetween(0, 999999),
            'elo' => $this->faker->numberBetween(0, 999999),
        ];
    }
}
