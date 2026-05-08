<?php

namespace Database\Factories;

use App\Models\GuildRank;
use Illuminate\Database\Eloquent\Factories\Factory;

class GuildRankFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GuildRank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'guild_id' => $this->faker->numberBetween(0, 999999),
            'castle_id'  => $this->faker->numberBetween(0, 999999),
            'capture'  => $this->faker->numberBetween(0, 999999),
            'emperium'  => $this->faker->numberBetween(0, 999999),
            'treasure'  => $this->faker->numberBetween(0, 999999),
            'top_eco'  => $this->faker->numberBetween(0, 999999),
            'top_def'  => $this->faker->numberBetween(0, 999999),
            'invest_eco'  => $this->faker->numberBetween(0, 999999),
            'invest_def'  => $this->faker->numberBetween(0, 999999),
            'offensive_score'  => $this->faker->numberBetween(0, 999999),
            'defensive_score'  => $this->faker->numberBetween(0, 999999),
            'posesion_time'  => $this->faker->numberBetween(0, 999999),
            'zeny_eco'  => $this->faker->numberBetween(0, 999999),
            'zeny_def'  => $this->faker->numberBetween(0, 999999),
            'skill_battleorder'  => $this->faker->numberBetween(0, 999999),
            'skill_regeneration'  => $this->faker->numberBetween(0, 999999),
            'skill_restore'  => $this->faker->numberBetween(0, 999999),
            'skill_emergencycall'  => $this->faker->numberBetween(0, 999999),
            'off_kill'  => $this->faker->numberBetween(0, 999999),
            'off_death'  => $this->faker->numberBetween(0, 999999),
            'def_kill'  => $this->faker->numberBetween(0, 999999),
            'def_death'  => $this->faker->numberBetween(0, 999999),
            'ext_kill'  => $this->faker->numberBetween(0, 999999),
            'ext_death'  => $this->faker->numberBetween(0, 999999),
            'ali_kill'  => $this->faker->numberBetween(0, 999999),
            'ali_death'  => $this->faker->numberBetween(0, 999999),
        ];
    }
}
