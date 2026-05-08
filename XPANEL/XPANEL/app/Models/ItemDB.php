<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemDB extends Model
{
    use HasFactory;
    protected $table = 'item_db';
    public $timestamps  = false;
    protected $fillable = ['id', 'name', 'type', 'price', 'weight', 'atk', 'def', 'range', 'slots', 'refineable', 'equip_level_min', 'armor_level'];

    public function cartInventory()
    {
        return $this->hasMany(CartInventory::class, 'nameid');
    }

    public function picklog()
    {
        return $this->hasMany(PickLog::class, 'nameid')->where('type', 'V');
    }
}
