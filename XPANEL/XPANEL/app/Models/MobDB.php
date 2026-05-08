<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobDB extends Model
{
    use HasFactory;

    protected $table = 'mob_db';
    public $timestamps  = false;

    protected $fillable = [
        'name_english',
        'mode_mvp'
    ];

    public function mvpLog()
    {
        return $this->hasMany(MvpLog::class, 'monster_id', 'id');
    }
}
