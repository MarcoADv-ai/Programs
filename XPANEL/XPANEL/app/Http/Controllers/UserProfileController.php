<?php

namespace App\Http\Controllers;

use App\Helpers\Emblem\Emblem;
use App\Models\Char;
use App\Models\WoeRank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    public function bg_profile($char_id, Request $request)
    {
        $basic_info = Char::select('char')
            ->select(['char.char_id', 'char.class', 'char.max_hp', 'char.max_sp', 'char.name', 'char.guild_id', 'guild.name as gName', 'rb.*'])
            ->leftJoin('guild', 'guild.guild_id', '=', 'char.guild_id')
            ->leftJoin('rank_bg as rb', 'char.char_id', '=', 'rb.char_id')
            ->where('char.char_id', '=', $char_id)
            ->get(10);

        $skill_count = DB::table('rank_bg_skill_count')
            ->select(['id', 'count'])
            ->where('rank_bg_skill_count.char_id', '=', $char_id)
            ->orderByDesc('rank_bg_skill_count.count')
            ->get(10);

        $kill_log = DB::table('rank_bg_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class'])
            ->join('char', 'rbl.killed_id', '=', 'char.char_id')
            ->where('rbl.killer_id', '=', $char_id)
            ->groupBy('rbl.killed')
            ->get(10);

        $death_log = DB::table('rank_bg_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class'])
            ->join('char', 'rbl.killer_id', '=', 'char.char_id')
            ->where('rbl.killed_id', '=', $char_id)
            ->groupBy('rbl.killer')
            ->get(10);

        $assasin = $request->input('assasin', null);
        $compare_death_log = DB::table('rank_bg_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class', 'char.name'])
            ->join('char', 'rbl.killer_id', '=', 'char.char_id')
            ->where('rbl.killed_id', '=', $char_id)
            ->where('rbl.killer_id', '=', $assasin)
            ->take(10)
            ->get();
        $compare_death_log = collect($compare_death_log)->sortByDesc('time')->values()->all();

        $victim = $request->input('victim', null);
        $compare_kill_log = DB::table('rank_bg_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class', 'char.name'])
            ->join('char', 'rbl.killer_id', '=', 'char.char_id')
            ->where('rbl.killer_id', '=', $char_id)
            ->where('rbl.killed_id', '=', $victim)
            ->take(10)
            ->get();
        $compare_kill_log = collect($compare_kill_log)->sortByDesc('time')->values()->all();


        $skill_id = $request->input('sid', null);
        $skill_rank = DB::table('rank_bg_skill_count')
            ->select(['*'])
            ->leftJoin('char', 'rank_bg_skill_count.char_id', '=', 'char.char_id')
            ->where('rank_bg_skill_count.id', '=', $skill_id)
            ->orderByDesc('rank_bg_skill_count.count')
            ->take(10)
            ->get();
        $skill_rank = collect($skill_rank)->sortByDesc('count')->values()->all();

        return Inertia::render(
            'UserProfile/Bg',
            ['skill_rank' => $skill_rank,   'compare_kill_log' => $compare_kill_log, 'compare_death_log' => $compare_death_log, 'char_id' => $char_id, 'basic_info' => $basic_info, 'skill_count' => $skill_count, 'kill_log' => $kill_log, 'death_log' => $death_log]
        );
    }

    public function woe_profile($char_id, Request $request)
    {
        $lastWoe = WoeRank::select(['date', 'type'])->orderByDesc('date')->first() ?? ['date' => null, 'type' => null];
        $woeId = $request->input('woe_id', $lastWoe['type']) ?? $lastWoe['type'];
        $woeDates = WoeRank::select(['date'])
                    ->groupBy('date')
                    ->where('type', $request->input('woe_id', $lastWoe['type']))
                    ->where('char_id', $char_id)
                    ->orderByDesc('date')->get();
        $woeDate = $request->input('woe_day',  $lastWoe['date']) ?? $lastWoe['date'];
        $woePlayed = WoeRank::select(['type as id'])
                     ->where('char_id', $char_id)
                     ->distinct()
                     ->get();

        $basic_info = Char::select([
            'char.char_id', 
            'char.class', 
            'char.max_hp', 
            'char.max_sp', 
            'char.name', 
            'char.guild_id',
            'guild.name AS guild_name',
            'guild.guild_id',
            'guild.emblem_len',
            'guild.emblem_data', 
            'rb.*'
        ])
            ->leftJoin('guild', 'guild.guild_id', '=', 'char.guild_id')
            ->leftJoin('rank_woe as rb', 'char.char_id', '=', 'rb.char_id')
            ->where('char.char_id', '=', $char_id)
            ->where('rb.date', 'LIKE', ($woeDate ? $woeDate : $lastWoe['date']) . '%')
            ->where('rb.type', '=', $woeId ?? $lastWoe['type'])
            ->take(10)
            ->get()
            ->transform(function ($item) {
            $emblem = new Emblem($item->guild_id, $item->emblem_data, $item->emblem_len);
            if ($item->guild_id != 0) {
                $item->emblem = $emblem->getEmblem();
            }
            return $item;
        });

        $skill_count = DB::connection('mysql')->table('rank_skill_count')
            ->select(['*'])
            ->where('rank_skill_count.char_id', '=', $char_id)
            ->where('rank_skill_count.date', '=', ($woeDate ? $woeDate : $lastWoe['date']))
            ->where('rank_skill_count.type', '=', $woeId)
            ->orderByDesc('rank_skill_count.count')
            ->groupBy('id')
            ->take(10)
            ->get();

        $kill_log = DB::connection('mysql')->table('char_woe_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class'])
            ->join('char', 'rbl.killed_id', '=', 'char.char_id')
            ->where('rbl.killer_id', '=', $char_id)
            ->where('rbl.time', 'LIKE', ($woeDate ? $woeDate . '%' : $lastWoe['date'] . '%'))
            ->groupBy('rbl.killed')
            ->take(10)
            ->get();

        $death_log = DB::connection('mysql')->table('char_woe_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class'])
            ->join('char', 'rbl.killer_id', '=', 'char.char_id')
            ->where('rbl.killed_id', '=', $char_id)
            ->where('rbl.time', 'LIKE', ($woeDate ? $woeDate . '%' : $lastWoe['date'] . '%'))
            ->groupBy('rbl.killer')
            ->take(10)
            ->get();


        $assasin = $request->input('assasin', null);
        $compare_death_log = DB::connection('mysql')->table('char_woe_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class', 'char.name'])
            ->join('char', 'rbl.killer_id', '=', 'char.char_id')
            ->where('rbl.killed_id', '=', $char_id)
            ->where('rbl.killer_id', '=', $assasin)
            ->where('rbl.time', 'LIKE', ($woeDate ? $woeDate . '%' : $lastWoe['date']) . '%')
            ->take(10)
            ->get();


        $victim = $request->input('victim', null);
        $compare_kill_log = DB::connection('mysql')->table('char_woe_log as rbl')
            ->select(['rbl.time', 'rbl.killer', 'rbl.killer_id', 'rbl.killed', 'rbl.killed_id', 'rbl.map', 'skill', 'char.class', 'char.name'])
            ->join('char', 'rbl.killer_id', '=', 'char.char_id')
            ->where('rbl.killer_id', '=', $char_id)
            ->where('rbl.killed_id', '=', $victim)
            ->where('rbl.time', 'LIKE', ($woeDate ? $woeDate . '%' : $lastWoe['date']) . '%')
            ->take(10)
            ->get();


        $skill_id = $request->input('sid');
        $skill_rank = DB::connection('mysql')->table('rank_skill_count')
            ->select(['*'])
            ->join('char', 'rank_skill_count.char_id', '=', 'char.char_id')
            ->where('rank_skill_count.id', '=', $skill_id)
            ->where('rank_skill_count.date', 'LIKE', ($woeDate ? $woeDate : $lastWoe['date']))
            ->where('rank_skill_count.type', '=', $woeId)
            ->groupBy('rank_skill_count.char_id')
            ->orderByDesc('rank_skill_count.count')
            ->take(10)
            ->get();


        return Inertia::render(
            'UserProfile/Woe',
            [
                'skill_rank'        =>      $skill_rank,        'compare_kill_log' =>   $compare_kill_log,
                'compare_death_log' =>      $compare_death_log, 'char_id'          =>   $char_id,
                'basic_info'        =>      $basic_info,        'skill_count'      =>   $skill_count,
                'kill_log'          =>      $kill_log,          'death_log'        =>   $death_log,
                'woe_dates'         =>      $woeDates,          'woe_played'       =>   $woePlayed,
            ]
        );
    }
}
