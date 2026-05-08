<?php

namespace Database\Factories;

use App\Models\GameAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class GameAccountFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GameAccount::class;

    private static $account_id = 2000000;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'account_id' => self::$account_id++,
            'userid' => $this->faker->unique()->lastName(),
            'user_pass' => '123456',
            'sex'   =>  $this->faker->randomElement($array = array('M', 'F')),
            'email' =>  $this->faker->email,
            'master_acc_id' => 1
        ];
    }
}
