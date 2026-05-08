<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PickLog extends Model
{
    use HasFactory;
    protected $table = "picklog";
    public $timestamps = false;


    protected $fillable = ['id', 'time', 'char_id', 'type', 'nameid', 'item_name', 'amount', 'refine', 'card0', 'card1', 'card2', 'card3'];

    public function vendings()
    {
        return $this->belongsTo(Vendings::class, 'char_id');
    }
    public function getTimeAttribute($value)
    {
        return Carbon::parse($value);
    }

    public function itemDB()
    {
        return $this->belongsTo(ItemDB::class, 'nameid');
    }

}
