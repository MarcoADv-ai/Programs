<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BgRank extends Model
{
    use HasFactory;
    const TABLE_NAME = 'rank_bg';
    protected $table = self::TABLE_NAME;
    protected $primaryKey = 'char_id';
    public $timestamps  = false;


    public function scopeFilter($query, $filterType, $class, $querySearch)
    {

        $classes = explode(',', $class);
        // dd($filterType);
        $rankTypes = [
            "GW" => self::TABLE_NAME.".win",
            "GT" => self::TABLE_NAME.".tie",
            "GL" => self::TABLE_NAME.".lost",
            "KC" => self::TABLE_NAME.".kill_count",
            "DC" => self::TABLE_NAME.".death_count",
            "DD" => self::TABLE_NAME.".damage_done",
            "DR" => self::TABLE_NAME.".damage_received",
            "GSS" => self::TABLE_NAME.".support_skills_used",
            "WSS" => self::TABLE_NAME.".wrong_support_skills_used",
            "TGH" => self::TABLE_NAME.".healing_done",
            "TWH" => self::TABLE_NAME.".wrong_healing_done",
            "HPP" => self::TABLE_NAME.".hp_heal_potions",
            "SPP" => self::TABLE_NAME.".sp_heal_potions",
            "YGU" => self::TABLE_NAME.".yellow_gemstones",
            "RGU" => self::TABLE_NAME.".red_gemstones",
            "BGU" => self::TABLE_NAME.".blue_gemstones",
            "ACC" => self::TABLE_NAME.".acid_demostration",
            "AU" => self::TABLE_NAME.".ammo_used"
        ];
        $type = $rankTypes[$filterType] ?? null;
        
        if (!in_array('All', $classes)) {
            $query = $query->whereIn('char.class', $classes);
        }

        $query = $query->where('char.name', 'like', '%'.$querySearch.'%');
        $query = $query->orderBy($type, 'DESC');

        return $query;
    }
}
