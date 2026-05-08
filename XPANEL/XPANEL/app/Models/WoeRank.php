<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WoeRank extends Model
{
    use HasFactory;
    protected $table = 'rank_woe';
    protected $primaryKey = 'char_id';
    public $timestamps  = false;

    public function scopeFilter($query, $filterType, $class, $woeDate, $woeType, $querySearch){
        $classes = explode(',', $class);
        $rankTypes = [
            'KC'   =>  'rank_woe.kill_count',
            'DC'   =>  'rank_woe.death_count',
            'DD'   =>  'rank_woe.damage_done',
            'DR'   =>  'rank_woe.damage_received',
            'GSS'  =>  'rank_woe.support_skills_used',
            'WSS'  =>  'rank_woe.wrong_support_skills_used',
            'TGH'  =>  'rank_woe.healing_done',
            'TWH'  =>  'rank_woe.wrong_healing_done',
            'HPP'  =>  'rank_woe.hp_heal_potions',
            'SPP'  =>  'rank_woe.sp_heal_potions',
            'YGU'  =>  'rank_woe.yellow_gemstones',
            'RGU'  =>  'rank_woe.red_gemstones',
            'BGU'  =>  'rank_woe.blue_gemstones',
            'ACC'  =>  'rank_woe.acid_demostration',
            'AU'   =>  'rank_woe.ammo_used'
        ];
        $type = $rankTypes[$filterType] ?? null;
        
        if (!in_array('All', $classes)) {
            $query = $query->whereIn('char.class', $classes);
        }
        $query = $query->where($type, '>=', 0);
        $query = $query->where('rank_woe.date', $woeDate);
        $query = $query->where('rank_woe.type', $woeType);
        $query = $query->where('login.group_id', '<', '80');
        $query = $query->where('char.name', 'like', '%'.$querySearch.'%');
        $query = $query->orderBy($type, 'DESC');
        return $query;
    }
}
