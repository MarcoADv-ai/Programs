<?php

namespace Database\Factories;

use App\Models\GuildPosition;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuildPositionFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuildPosition::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'guild_id' => $this->faker->numberBetween(100000, 999999),
            'position' => $this->faker->numberBetween(0, 100),
            'name' => $this->faker->word,
            'mode' => $this->faker->numberBetween(0, 999),
            'exp_mode' => $this->faker->randomNumber(1),
        ];
    }
}
