<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CharRegNumFactory extends Factory
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
            'char_id' => self::$char_id++,
            'key' => 'MVPRank',
            'index' => 0,
            'value' => $this->faker->numberBetween(0, 40000),
        ];
    }
}
