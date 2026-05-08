<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildRank extends Model
{
    use HasFactory;
    protected $table = 'guild_rank';
    public $timestamps  = false;
}
