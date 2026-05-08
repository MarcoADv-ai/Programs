<?php

namespace Database\Factories;

use App\Models\BgRank;
use Illuminate\Database\Eloquent\Factories\Factory;

class BgRankFactory extends Factory
{
    private static $char_id = 150000;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BgRank::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'char_id' => self::$char_id++,
            'top_damage'=> $this->faker->numberBetween(0,9999),
            'damage_done'=> $this->faker->numberBetween(0,9999),
            'damage_received'=> $this->faker->numberBetween(0,9999),
            'cq_emperium_kill'=> $this->faker->numberBetween(0,9999),
            'cq_barricade_kill'=> $this->faker->numberBetween(0,9999),
            'cq_gstone_kill'=> $this->faker->numberBetween(0,9999),
            'cq_wins'=> $this->faker->numberBetween(0,9999),
            'cq_lost'=> $this->faker->numberBetween(0,9999),
            'tvt_kills'=> $this->faker->numberBetween(0,9999),
            'tvt_deaths'=> $this->faker->numberBetween(0,9999),
            'tvt_wins'=> $this->faker->numberBetween(0,9999),
            'tvt_lost'=> $this->faker->numberBetween(0,9999),
            'tvt_tie'=> $this->faker->numberBetween(0,9999),
            'cs_emperium_kill'=> $this->faker->numberBetween(0,9999),
            'cs_barricade_kill'=> $this->faker->numberBetween(0,9999),
            'cs_gstone_kill'=> $this->faker->numberBetween(0,9999),
            'cs_wins'=> $this->faker->numberBetween(0,9999),
            'cs_lost'=> $this->faker->numberBetween(0,9999),
            'cs_tie'=> $this->faker->numberBetween(0,9999),
            'dte_emperium_kill'=> $this->faker->numberBetween(0,9999),
            'dte_wins'=> $this->faker->numberBetween(0,9999),
            'dte_lost'=> $this->faker->numberBetween(0,9999),
            'dte_tie'=> $this->faker->numberBetween(0,9999),
            'bz_wins'=> $this->faker->numberBetween(0,9999),
            'bz_lost'=> $this->faker->numberBetween(0,9999),
            'bz_tie'=> $this->faker->numberBetween(0,9999),
            'kill_count'=> $this->faker->numberBetween(0,9999),
            'death_count'=> $this->faker->numberBetween(0,9999),
            'win'=> $this->faker->numberBetween(0,9999),
            'lost'=> $this->faker->numberBetween(0,9999),
            'tie'=> $this->faker->numberBetween(0,9999),
            'leader_win'=> $this->faker->numberBetween(0,9999),
            'leader_lost'=> $this->faker->numberBetween(0,9999),
            'leader_tie'=> $this->faker->numberBetween(0,9999),
            'deserter'=> $this->faker->numberBetween(0,9999),
            'score'=> $this->faker->numberBetween(0,9999),
            'points'=> $this->faker->numberBetween(0,9999),
            'sp_heal_potions'=> $this->faker->numberBetween(0,9999),
            'hp_heal_potions'=> $this->faker->numberBetween(0,9999),
            'yellow_gemstones'=> $this->faker->numberBetween(0,9999),
            'red_gemstones'=> $this->faker->numberBetween(0,9999),
            'blue_gemstones'=> $this->faker->numberBetween(0,9999),
            'poison_bottles'=> $this->faker->numberBetween(0,9999),
            'acid_demostration'=> $this->faker->numberBetween(0,9999),
            'acid_demostration_fail'=> $this->faker->numberBetween(0,9999),
            'support_skills_used'=> $this->faker->numberBetween(0,9999),
            'healing_done'=> $this->faker->numberBetween(0,9999),
            'wrong_support_skills_used'=> $this->faker->numberBetween(0,9999),
            'wrong_healing_done'=> $this->faker->numberBetween(0,9999),
            'sp_used'=> $this->faker->numberBetween(0,9999),
            'zeny_used'=> $this->faker->numberBetween(0,9999),
            'spiritb_used'=> $this->faker->numberBetween(0,9999),
            'ammo_used'=> $this->faker->numberBetween(0,9999),
        ];
    }
}
