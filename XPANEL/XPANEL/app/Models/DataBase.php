<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataBase extends Model
{
    use HasFactory;
    protected $table = 'item_db';
    protected $primaryKey = 'id';
    public $timestamps  = false;

    public function scopeFilter($query, $name)
    {
        $query = $query->where('name_english', 'like', '%'.$name.'%');
        $query = $query->orWhere('id', 'like', '%'.$name.'%');
        $query = $query->orderBy('name_english', 'ASC');
        return $query;
    }
}
