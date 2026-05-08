<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PvpRank extends Model
{
    use HasFactory;
    protected $table = 'pvp_rank';
    public $timestamps  = false;


    public function scopeFilter($query, $filterType, $class, $querySearch){
        $classes = explode(',', $class);
        $rankTypes = [
            'D' => 'pvp_rank.dead',
            'K' => 'pvp_rank.kill',
            'E' => 'pvp_rank.point'
        ];
        $type = $rankTypes[$filterType] ?? null;

        if (!in_array('All', $classes)) {
            $query = $query->whereIn('char.class', $classes);
        }       
        $query = $query->where('char.name', 'like', '%'.$querySearch.'%');
        $query = $query->where($type, '>', 0);
        $query = $query->orderBy($type, 'DESC');
        return $query;
    }
}
