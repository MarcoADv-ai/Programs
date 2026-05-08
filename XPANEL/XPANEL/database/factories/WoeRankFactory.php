<?php

namespace Database\Factories;

use App\Models\WoeRank;
use Illuminate\Database\Eloquent\Factories\Factory;

class WoeRankFactory extends Factory
{
    private static $char_id = 150000;
    private static $guild_id = 0;


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = WoeRank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'char_id' => self::$char_id++,
            'date' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'guild_id' => self::$guild_id++,
            'kill_count' => $this->faker->numberBetween(0, 999999),
            'death_count' => $this->faker->numberBetween(0, 999999),
            'score' => $this->faker->numberBetween(0, 999999),
            'top_damage' => $this->faker->numberBetween(0, 999999),
            'damage_done' => $this->faker->numberBetween(0, 999999),
            'damage_received' => $this->faker->numberBetween(0, 999999),
            'emperium_damage' => $this->faker->numberBetween(0, 999999),
            'guardian_damage' => $this->faker->numberBetween(0, 999999),
            'barricade_damage' => $this->faker->numberBetween(0, 999999),
            'gstone_damage' => $this->faker->numberBetween(0, 999999),
            'emperium_kill' => $this->faker->numberBetween(0, 999999),
            'guardian_kill' => $this->faker->numberBetween(0, 999999),
            'barricade_kill' => $this->faker->numberBetween(0, 999999),
            'gstone_kill' => $this->faker->numberBetween(0, 999999),
            'sp_heal_potions' => $this->faker->numberBetween(0, 999999),
            'hp_heal_potions' => $this->faker->numberBetween(0, 999999),
            'yellow_gemstones' => $this->faker->numberBetween(0, 999999),
            'red_gemstones' => $this->faker->numberBetween(0, 999999),
            'blue_gemstones' => $this->faker->numberBetween(0, 999999),
            'poison_bottles' => $this->faker->numberBetween(0, 999999),
            'acid_demostration' => $this->faker->numberBetween(0, 999999),
            'acid_demostration_fail' => $this->faker->numberBetween(0, 999999),
            'support_skills_used' => $this->faker->numberBetween(0, 999999),
            'healing_done' => $this->faker->numberBetween(0, 999999),
            'wrong_support_skills_used' => $this->faker->numberBetween(0, 999999),
            'wrong_healing_done' => $this->faker->numberBetween(0, 999999),
            'sp_used' => $this->faker->numberBetween(0, 999999),
            'zeny_used' => $this->faker->numberBetween(0, 999999),
            'spiritb_used' => $this->faker->numberBetween(0, 999999),
            'ammo_used' => $this->faker->numberBetween(0, 999999),
        ];
    }
}
