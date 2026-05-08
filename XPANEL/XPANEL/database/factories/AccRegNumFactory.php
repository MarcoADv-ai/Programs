<?php

namespace Database\Factories;

use App\Models\AccRegNum;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccRegNumFactory extends Factory
{
    private static $account_id = 2000000;


    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    // protected $model = AccRegNum::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_id' => self::$account_id++,
            'key' => '#CASHPOINTS',
            'index' => 0,
            'value' => $this->faker->randomNumber(4),
        ];
    }
}
