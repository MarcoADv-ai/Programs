<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CharBgLog extends Model
{
    use HasFactory;
    protected $table = 'char_bg_log';
    protected $primaryKey = 'id';
    public $timestamps  = false;
}
