<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RankWoeLog extends Model
{
    use HasFactory;
    protected $table = "rank_woe_log";
    protected $primaryKey = 'id';
    public $timestamps = false;
}
