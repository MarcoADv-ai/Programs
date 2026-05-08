<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Vendings extends Model
{
    use HasFactory;

    protected $table = 'vendings';
    public $timestamps  = false;

    protected $fillable = [
        'id',
        'account_id',
        'char_id',
        'sex',
        'map',
        'x',
        'y',
        'title',
        'body_direction',
        'head_direction',
        'sit',
        'autotrade',
    ];


    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->table = 'autotrade_merchants';
            $this->fillable = [
                'account_id',
                'char_id',
                'sex',
                'title',
            ];
        }
    }

    public function char()
    {
        return $this->belongsTo(Char::class, 'char_id');
    }

    public function login()
    {
        return $this->belongsTo(GameAccount::class);
    }

    public function vendingItems()
    {
        return $this->hasMany(VendingItems::class, 'vending_id');
    }
}
