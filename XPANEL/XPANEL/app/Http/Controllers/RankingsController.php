<?php

namespace App\Http\Controllers;

use App\Helpers\Emblem\Emblem;
use App\Helpers\FunctionsHelper;
use App\Models\AccRegNum;
use App\Models\BgRank;
use App\Models\Char;
use App\Models\MvpRank;
use App\Models\PvpRank;
use App\Models\Guild;
use App\Models\MobDB;
use App\Models\MvpLog;
use App\Models\WoeRank;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class RankingsController extends Controller
{

    public function leveling()
    {
        $baseLevel = Char::select([
            'char.name as char_name',
            'char.class',
            'char.base_exp',
            'char.base_level',
            'char.job_level',
            'users.country',
            'guild.name AS guild_name',
            'guild.guild_id',
            'guild.emblem_len',
            'guild.emblem_data',
            'char.last_map',
            'char.online'
        ])
            ->join('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->where('login.group_id', '<', '80')
            ->orderBy('base_level', 'desc')
            ->paginate(15);

        $baseLevel->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Leveling', ['base_level' => $baseLevel]);
    }

    public function zeny()
    {
        $zeny = Char::where('zeny', '>', 0)
            ->select([
                'zeny as zeny',
                'char.name as char_name',
                'char.class',
                'char.base_level',
                'users.country',
                'char.job_level',
                'guild.name AS guild_name',
                'guild.emblem_len',
                'guild.emblem_data',
                'guild.guild_id',

            ])
            ->join('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->orderBy('zeny', 'desc')
            ->groupBy('char.account_id')
            ->paginate(15);

        $zeny->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Zeny', ['zeny' => $zeny]);
    }

    public function battleground(Request $request)
    {
        $class = $request->input('class', 'All') ?? 'All';
        $filterType = request()->input('filter_type', 'GW') ?? 'GW';
        $querySearch = $request->input('q', '');
        $bgData = BgRank::select([
            'users.country',
            'char.name',
            'char.guild_id',
            'char.class',
            'char.playtime',
            'guild.name as gname',
            'guild.emblem_len',
            'guild.emblem_data',
            'rank_bg.*'
        ])
            ->join('char', 'rank_bg.char_id', '=', 'char.char_id')
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->leftJoin('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->where('login.group_id', '<', '80')
            ->where('rank_bg.score', '>=', '0')
            ->filter($filterType, $class, $querySearch)
            ->paginate(15);

        $bgData->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Battleground', ['bg_data' => $bgData]);
    }

    public function cashPoints(Request $request)
    {
        $class = $request->input('class', 'All') ?? 'All';
        $querySearch = $request->input('q', '');
        $filterType = request()->input('filter_type', 'A') ?? 'A';
        $cashPoints = AccRegNum::select([
            'char.name', 'char.account_id', 'char.guild_id', 'guild.name as GName',
            'guild.emblem_len',
            'guild.emblem_data',
            'char.class as  job',  'char.base_level as lvl',
            'char.job_level as blvl', 'char.online',
            'users.country', 'acc_reg_num.value as points'
        ])
            ->join('char', 'char.account_id', '=', 'acc_reg_num.account_id')
            ->leftJoin('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->join('login', 'login.account_id', '=', 'char.account_id')
            ->join('users', 'users.id', '=', 'login.master_acc_id')
            ->where('login.group_id', '<', '80')
            ->where('acc_reg_num.key', '=', '#CASHPOINTS')
            ->filter($filterType, $class, $querySearch)
            ->paginate(15);

        $cashPoints->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/CashPoints', ['cash_points' => $cashPoints]);
    }

    public function guild()
    {
        $guilds = Guild::select([
            'guild_id', 'name', 'guild.emblem_len',
            'guild.emblem_data', 'guild_lv', 'exp', 'guild_id', 'average_lv'
        ])
            ->orderByDesc('guild.exp')
            ->paginate(20);

        $guilds->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Guild', ['guilds' => $guilds]);
    }

    public function deaths(Request $request)
    {
        $class = $request->input('class', 'All') ?? 'All';
        $querySearch = $request->input('q', '');
        $filterType = request()->input('filter_type', 'D') ?? 'D';

        $deathsPlayer = Char::select([
            'char.char_id',
            'char.name AS char_name',
            'users.country',
            'char.class',
            'char.base_level',
            'char.job_level',
            'guild.guild_id',
            'guild.name AS guild_name',
            'guild.emblem_len',
            'guild.emblem_data',
            DB::raw("CAST(IFNULL(char_reg_num.value, '0') AS UNSIGNED) AS death_count")
        ])
            ->leftJoin('guild', 'guild.guild_id', '=', 'char.guild_id')
            ->leftJoin('guild_emblems', 'guild_emblems.guild_id', '=', 'char.guild_id')
            ->leftJoin('login', 'login.account_id', '=', 'char.account_id')
            ->leftJoin('users', 'users.id', '=', 'login.master_acc_id')
            ->leftJoin(DB::raw("(SELECT char_id, value FROM char_reg_num WHERE char_reg_num.key = 'PC_DIE_COUNTER') AS char_reg_num"), 'char_reg_num.char_id', '=', 'char.char_id')
            ->where('login.group_id', '<', '80')
            ->filter($filterType, $class, $querySearch)
            ->paginate(15);

        $deathsPlayer->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Deaths', ['deaths_player' => $deathsPlayer]);
    }

    public function mvps(Request $request)
    {
        $mvpList = MobDB::select(['id', 'name_english'])->where('mode_mvp', 1)->get();
        $filterType = $request->input('filter_type', 'MK') ?? 'MK';
        $class = $request->input('class', 'All') ?? 'All';
        $querySearch = $request->input('q', '');
        $charId = $request->input('char_id', null);

        if (isset($charId) && $charId != null) {
            $mvpKills = MvpLog::select([
                'mvplog.monster_id',
                'mob_db.name_english',
                DB::raw('MAX(mvplog.mvp_date) as latest_mvp_date'),
                DB::raw('COUNT(mvplog.monster_id) as kills'),
                'mvplog.map as latest_map'
            ])
                ->join('mob_db', 'mvplog.monster_id', '=', 'mob_db.id')
                ->where('kill_char_id', $charId)
                ->whereBetween('mvplog.mvp_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->groupBy('mvplog.monster_id')
                ->orderBy('kills', 'desc')
                ->get();

            foreach ($mvpKills as $mvpKill) {
                $latestMvpLog = MvpLog::where('monster_id', $mvpKill->monster_id)
                    ->where('mvp_date', $mvpKill->latest_mvp_date)
                    ->first();

                if ($latestMvpLog) {
                    $mvpKill->latest_map = $latestMvpLog->map;
                }
            }
        }

        $mvpsCount = MvpRank::select([
            'char.char_id', 'char.name', 'char.class', 'char.base_level', 'char.job_level',
            'char.last_map', 'char.online', 'login.sex', 'guild.name as GName', 'guild.emblem_len',
            'guild.emblem_data', 'guild.guild_id', 'mvp_rank.mvp_kills', 'users.country'
        ])
            ->join('char', 'mvp_rank.char_id', '=', 'char.char_id')
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->leftJoin('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->where('login.group_id', '<', '80')
            ->filter($filterType, $class, $querySearch)
            ->paginate(15);

        $mvpsCount->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);

            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Mvps', ['mvps_count' => $mvpsCount, 'mvp_kills' => $mvpKills ?? []]);
    }

    public function playtime(Request $request)
    {
        $filterType = $request->input('filter_type', 'PT') ?? 'PT';
        $class = $request->input('class', 'All') ?? 'All';
        $querySearch = $request->input('q', '');
        $playtime = Char::select([
            'char.name', 'guild.name as GName',                    'guild.emblem_len',
            'guild.emblem_data', 'char.guild_id', 'users.country', 'char.class', 'char.base_level', 'char.job_level',
            'char.last_map', 'char.online', 'char.playtime'
        ])
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->leftJoin('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->where('login.group_id', '<', '80')
            ->filter($filterType, $class, $querySearch)
            ->paginate(15);

        $playtime->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);

            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        // dd($playtime);
        return Inertia::render('Rankings/Playtime', ['playtime' => $playtime]);
    }

    public function pvp(Request $request)
    {
        $class = $request->input('class', 'All') ?? 'All';
        $filterType = request()->input('filter_type', 'E') ?? 'E';
        $querySearch = request()->input('q', '');
        $pvp = PvpRank::select([
            'users.country', 'pvp_rank.char_id', 'pvp_rank.dead', 'pvp_rank.kill', 'pvp_rank.point', 'char.name',
            'char.class', 'char.online', 'char.char_id', 'char.guild_id', 'guild.name as GName',
            'guild.emblem_len',
            'guild.emblem_data'
        ])
            ->join('char', 'pvp_rank.char_id', '=', 'char.char_id')
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->leftJoin('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->where('login.group_id', '<', '80')
            ->filter($filterType, $class, $querySearch)
            ->paginate(15);

        $pvp->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);

            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Pvp', ['pvp' => $pvp]);
    }

    public function woe(Request $request)
    {
        $lastWoe = WoeRank::select(['date', 'type'])->orderByDesc('date')->first() ?? ['date' => null, 'type' => null];
        $woeDates = WoeRank::select(['date'])->groupBy('date')->where('type', $request->input('woe_id', $lastWoe['type']))->orderByDesc('date')->get();
        $class = $request->input('class', 'All') ?? 'All';
        $woeId = $request->input('woe_id', $lastWoe['type']) ?? $lastWoe['type'];
        $woeDate = $request->input('woe_day',  $lastWoe['date']) ?? $lastWoe['date'];
        $filterType = $request->input('filter_type', 'DD') ?? 'DD';
        $querySearch = $request->input('q', '');

        $woe = WoeRank::select([
            'users.country',
            'char.char_id',
            'char.name',
            'char.guild_id',
            'char.class',
            'guild.name as GName',
            'guild.emblem_len',
            'guild.emblem_data',
            'rank_woe.type',
            'rank_woe.score',
            'rank_woe.kill_count',
            'rank_woe.death_count',
            'rank_woe.top_damage',
            'rank_woe.damage_done',
            'rank_woe.damage_received',
            'rank_woe.emperium_damage',
            'rank_woe.guardian_damage',
            'rank_woe.barricade_damage',
            'rank_woe.gstone_damage',
            'rank_woe.emperium_kill',
            'rank_woe.guardian_kill',
            'rank_woe.barricade_kill',
            'rank_woe.gstone_kill',
            'rank_woe.sp_heal_potions',
            'rank_woe.hp_heal_potions',
            'rank_woe.yellow_gemstones',
            'rank_woe.red_gemstones',
            'rank_woe.blue_gemstones',
            'rank_woe.poison_bottles',
            'rank_woe.acid_demostration',
            'rank_woe.acid_demostration_fail',
            'rank_woe.support_skills_used',
            'rank_woe.healing_done',
            'rank_woe.wrong_support_skills_used',
            'rank_woe.wrong_healing_done',
            'rank_woe.sp_used',
            'rank_woe.zeny_used',
            'rank_woe.spiritb_used',
            'rank_woe.ammo_used'
        ])
            ->join('char', 'rank_woe.char_id', '=', 'char.char_id')
            ->join('login', 'char.account_id', '=', 'login.account_id')
            ->leftJoin('guild', 'char.guild_id', '=', 'guild.guild_id')
            ->join('users', 'login.master_acc_id', '=', 'users.id')
            ->filter($filterType, $class, $woeDate, $woeId, $querySearch)
            ->orderByDesc('rank_woe.score')
            ->paginate(15);


        $woe->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);

            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        return Inertia::render('Rankings/Woe', ['woe' => $woe, 'woe_id' => $woeId, 'dates_woe' => $woeDates]);
    }

    public function woe_damage(Request $request)
    {
        $lastWoe = WoeRank::select(['date', 'type'])->orderByDesc('date')->first() ?? ['date' => null, 'type' => null];
        $woeDates = WoeRank::select(['date'])->groupBy('date')->where('type', $request->input('woe_id', $lastWoe['type']))->orderByDesc('date')->get();
        $woe_damage = WoeRank::select(DB::raw('
            SUM(rank_woe.damage_done) AS total_dmg,
            SUM(rank_woe.damage_received) AS total_dmg_rcvd,
            SUM(rank_woe.kill_count) AS total_kill,
            SUM(rank_woe.death_count) AS total_dead,
            (SUM(rank_woe.damage_done) - SUM(rank_woe.damage_received)) * 20 / 100 AS comp_damage,
            SUM(rank_woe.kill_count) - SUM(rank_woe.death_count) AS comp_kd,
            CASE
                WHEN SUM(rank_woe.damage_done) - SUM(rank_woe.damage_received) > 0 AND SUM(rank_woe.kill_count) - SUM(rank_woe.death_count) > 0 THEN
                    CASE
                        WHEN (SUM(rank_woe.damage_done) - SUM(rank_woe.damage_received)) > 10000000 THEN 10000000 * (SUM(rank_woe.kill_count) - SUM(rank_woe.death_count))
                        ELSE 8000000 * (SUM(rank_woe.kill_count) - SUM(rank_woe.death_count))
                    END
                WHEN SUM(rank_woe.damage_done) - SUM(rank_woe.damage_received) > 0 AND SUM(rank_woe.kill_count) - SUM(rank_woe.death_count) < 0 THEN
                    (SUM(rank_woe.damage_done) - SUM(rank_woe.damage_received)) / 80 * 100
                WHEN (SUM(rank_woe.damage_done) - SUM(rank_woe.damage_received)) / 80 < 0 AND SUM(rank_woe.kill_count) - SUM(rank_woe.death_count) > 0 THEN
                    7000000 * (SUM(rank_woe.kill_count) - SUM(rank_woe.death_count))
                ELSE 0
            END AS elo,
            rank_woe.guild_id,
            g.name as gname,
            g.master,
            g.emblem_len,
            g.emblem_data,
            c.class
        '))
            ->join('char AS c', 'rank_woe.char_id', '=', 'c.char_id')
            ->join('guild AS g', 'rank_woe.guild_id', '=', 'g.guild_id')
            ->where('damage_done', '>', 1000)
            ->where('rank_woe.guild_id', '<>', 1)
            ->where('rank_woe.date', $request->input('woe_day', $lastWoe['date']))
            ->where('rank_woe.type', $request->input('woe_id', $lastWoe['type']))
            ->groupBy('guild_id')
            ->orderByDesc('elo')
            ->paginate(15);
        // dd($woe_damage);
        $woe_damage->getCollection()->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);

            if ($item->guild_id != 0)
                $item->emblem = $emblem->getEmblem();

            return $item;
        });

        $allGuilds = DB::table('rank_woe')
            ->select([
                DB::raw('SUM(rank_woe.damage_done) AS total_dmg'),
                'rank_woe.guild_id',
                'guild.name',
                'guild.emblem_len',
                'guild.emblem_data',
            ])
            ->join('guild', 'rank_woe.guild_id', '=', 'guild.guild_id')
            ->where('damage_done', '>', '10000')
            ->where('rank_woe.guild_id', '<>', '1')
            ->where('rank_woe.date', '=', ($request->has('woe_day') ? $request->input('woe_day') : $lastWoe['date']))
            ->where('rank_woe.type', $request->input('woe_id', $lastWoe['type']))
            ->groupBy('guild_id')
            ->orderByDesc('damage_done')
            ->take(10)
            ->get();
                
        $guild_data = '    
            SELECT 
            (
                SELECT COUNT(1) FROM char_woe_log AS l 
                INNER JOIN `char` AS CKiller 
                ON CKiller.char_id = l.killer_id 
                INNER JOIN `char` AS CKilled 
                ON CKilled.char_id = l.killed_id
                WHERE
                    CKiller.guild_id = ?
                AND
                    CKilled.guild_id = ?
                AND
                    l.time LIKE ?                    
                ) AS kills,
            (
                SELECT COUNT(1) FROM char_woe_log AS l INNER JOIN `char` AS CKiller ON CKiller.char_id = l.killer_id INNER JOIN `char` AS CKilled ON CKilled.char_id = l.killed_id
                WHERE
                    CKiller.guild_id = ?
                AND
                    CKilled.guild_id = ?
                AND
                    l.time LIKE ?
            ) AS deaths
        ';

        $vs_data = array();
        foreach ($allGuilds as $value) {
            $guild_id = $value->guild_id;

            foreach ($allGuilds as $value) {
                $vs_guild_id = $value->guild_id;
                if ($guild_id == $vs_guild_id)
                    continue;
                $vs_data[$guild_id][$vs_guild_id] = DB::select($guild_data, [$guild_id, $vs_guild_id, ($request->has('woe_day') ? $request->input('woe_day') . '%' : $lastWoe['date'] . '%'), $vs_guild_id, $guild_id, ($request->has('woe_day') ? $request->input('woe_day') . '%' : $lastWoe['date'] . '%')]);
            }
        }
        // dd($vs_data);
        // dd($request->input('woe_id', $lastWoe['type']));
        // dd($woeDates);
        return Inertia::render('Rankings/WoeDamage', ['woe_damage' => (isset($woe_damage) ? $woe_damage : null), 'all_guilds' => (isset($allGuilds) ? $allGuilds : null), 'vs_data' => (isset($vs_data) ? $vs_data : null), 'dates_woe' => $woeDates, 'woe_id' => $request->input('woe_id', $lastWoe['type'])]);
    }
}
