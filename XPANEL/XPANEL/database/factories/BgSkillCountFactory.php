<?php

namespace Database\Factories;

use App\Models\BgSkillCount;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class BgSkillCountFactory extends Factory
{
    private static $char_id = 150000;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BgSkillCount::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'char_id' => self::$char_id++,
            'id' => $this->faker->numberBetween(0, 5000),
            'count' => $this->faker->numberBetween(100000, 999999),
        ];
    }
}
