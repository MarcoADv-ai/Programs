<?php

namespace Database\Factories;

use App\Models\Char;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

class CharFactory extends Factory
{

    private static $guild_id = 0;

    private $Global_Jobs = [
        0,
        1,
        2,
        3,
        4,
        5,
        6,
        7,
        8,
        9,
        10,
        11,
        12,
        13,
        15,
        16,
        17,
        18,
        19,
        20,
        21,
        22,
        24,
        25,
        26,
        4001,
        4002,
        4003,
        4004,
        4005,
        4006,
        4007,
        4008,
        4009,
        4010,
        4011,
        4012,
        4013,
        4014,
        4015,
        4016,
        4017,
        4018,
        4019,
        4020,
        4021,
        4022,
        4023,
        4024,
        4025,
        4026,
        4027,
        4028,
        4029,
        4030,
        4031,
        4032,
        4033,
        4034,
        4035,
        4036,
        4037,
        4038,
        4039,
        4040,
        4041,
        4042,
        4043,
        4044,
        4045,
        4046,
        4047,
        4048,
        4049,
    ];

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Char::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user_ids = DB::table('login')->select('account_id')->get();
        $user_id = $this->faker->randomElement($user_ids)->account_id;
        return [
            'account_id'          =>      $user_id,
            'char_num'            =>      $this->faker->randomNumber(2),
            'name'                =>      $this->faker->name(),
            'class'               =>      $this->Global_Jobs[rand(0, count($this->Global_Jobs) - 1)],
            'base_level'          =>      $this->faker->randomNumber(2),
            'job_level'           =>      $this->faker->randomNumber(2),
            'base_exp'            =>      $this->faker->randomNumber(7),
            'job_exp'             =>      $this->faker->randomNumber(7),
            'zeny'                =>      $this->faker->randomNumber(5),
            'str'                 =>      $this->faker->randomNumber(1),
            'agi'                 =>      $this->faker->randomNumber(1),
            'vit'                 =>      $this->faker->randomNumber(1),
            'int'                 =>      $this->faker->randomNumber(1),
            'dex'                 =>      $this->faker->randomNumber(1),
            'luk'                 =>      $this->faker->randomNumber(1),
            'pow'                 =>      $this->faker->randomNumber(1),
            'sta'                 =>      $this->faker->randomNumber(1),
            'wis'                 =>      $this->faker->randomNumber(1),
            'spl'                 =>      $this->faker->randomNumber(1),
            'con'                 =>      $this->faker->randomNumber(1),
            'crt'                 =>      $this->faker->randomNumber(1),
            'max_hp'              =>      $this->faker->randomNumber(4),
            'hp'                  =>      $this->faker->randomNumber(4),
            'max_sp'              =>      $this->faker->randomNumber(4),
            'sp'                  =>      $this->faker->randomNumber(2),
            'max_ap'              =>      $this->faker->randomNumber(2),
            'status_point'        =>      $this->faker->randomNumber(2),
            'skill_point'         =>      $this->faker->randomNumber(2),
            'guild_id'            =>      Self::$guild_id++,
            'playtime'            =>      $this->faker->randomNumber(5)
        ];
    }
}
