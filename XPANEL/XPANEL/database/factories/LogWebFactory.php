<?php

namespace Database\Factories;

use App\Models\LogWeb;
use Illuminate\Database\Eloquent\Factories\Factory;

class LogWebFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = LogWeb::class;

    public $log_type = [
        'login',
        'master_account_id',
        'logout',
        'register',
        'update',
        'delete',
        'create',
        'reset',
        'error',
        'other',
    ];
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'master_account_id' => $this->faker->randomNumber(1),
            'time' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'log_type' => $this->log_type[rand(0, count($this->log_type) - 1)],
            'ip' => $this->faker->ipv4(), // password
            'session'   => $this->faker->userAgent(),
        ];
    }
}
