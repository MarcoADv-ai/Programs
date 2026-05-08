<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvpRank extends Model
{
    use HasFactory;
    protected $table = 'mvp_rank';
    public $timestamps  = false;

    protected $fillable = [
        'mvp_kills',
    ];

    public function scopeFilter($query, $filterType, $class, $querySearch){
        $classes = explode(',', $class);
        $rankTypes = [
            'MK' => 'mvp_rank.mvp_kills',
            'S' => 'char.online',
        ];
        $type = $rankTypes[$filterType] ?? null;

        if (!in_array('All', $classes)) {
            $query = $query->whereIn('char.class', $classes);
        }
        
        $query = $query->where($type, '>', 0);
        $query = $query->where('char.name', 'like', '%'.$querySearch.'%');
        $query = $query->orderBy($type, 'DESC');
                
        return $query;
    }
}
