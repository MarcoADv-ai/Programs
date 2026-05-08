<?php

namespace Database\Seeders;

use App\Models\AccRegNum;
use App\Models\CharRegNum;
use App\Models\Guild;
use App\Models\BgSkillCount;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\GameAccount;
use App\Models\LogWeb;
use App\Models\Char;
use App\Models\BgRank;
use App\Models\CharBgLog;
use App\Models\WoeRank;
use App\Models\RankWoeLog;
use App\Models\GuildPosition;
use App\Models\GuildRank;
use App\Models\Homunculus;
use App\Models\PvpRank;

class DatabaseSeeder extends Seeder
{


    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'email' => 'a@a.com',
            'password' => bcrypt('123456'),
        ]);
        LogWeb::factory()->count(10)->create();
        GameAccount::factory()->count(200)->create();
        Char::factory()->count(200)->create();
        // BgSkillCount::factory()->count(30)->create();
        BgRank::factory()->count(200)->create();
        // CharBgLog::factory()->count(200)->create();
        WoeRank::factory()->count(200)->create();
        RankWoeLog::factory()->count(200)->create();
        GuildPosition::factory()->count(100)->create();
        GuildRank::factory()->count(100)->create();
        PvpRank::factory()->count(200)->create();
        // AccRegNum::factory()->count(200)->create();
        Guild::factory()->count(20)->create();
        // CharRegNum::factory()->count(200)->create();
    }
}
