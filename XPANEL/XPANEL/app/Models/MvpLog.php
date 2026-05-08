<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MvpLog extends Model
{
    use HasFactory;

    protected $table = 'mvplog';
    public $timestamps  = false;

    protected $fillable = [
        'kill_char_id',
        'monster_id',
    ];


    public function char()
    {
        return $this->belongsTo(Char::class, 'kill_char_id', 'char_id');
    }

    public function mob()
    {
        return $this->belongsTo(MobDB::class, 'monster_id', 'id');
    }
}
