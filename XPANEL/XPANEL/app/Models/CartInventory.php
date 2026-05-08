<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class CartInventory extends Model
{
    use HasFactory;
    protected $table = 'cart_inventory';
    public $timestamps  = false;

    protected $fillable = [
        'id',
        'char_id',
        'nameid',
        'amount',
        'equip',
        'identify',
        'refine',
        'attribute',
        'card0',
        'card1',
        'card2',
        'card3',
        'option_id0',
        'option_val0',
        'option_parm0',
        'option_id1',
        'option_val1',
        'option_parm1',
        'option_id2',
        'option_val2',
        'option_parm2',
        'option_id3',
        'option_val3',
        'option_parm3',
        'option_id4',
        'option_val4',
        'option_parm4',
        'expire_time',
        'bound',
        'unique_id',
        'enchantgrade',
    ];

    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->fillable = [
                'id',
                'char_id',
                'nameid',
                'amount',
                'equip',
                'identify',
                'refine',
                'grade',
                'attribute',
                'card0',
                'card1',
                'card2',
                'card3',
                'opt_idx0',
                'opt_val0',
                'opt_idx1',
                'opt_val1',
                'opt_idx2',
                'opt_val2',
                'opt_idx3',
                'opt_val3',
                'opt_idx4',
                'opt_val4',
                'expire_time',
                'bound',
                'unique_id',
            ];
        }
    }

    public function char()
    {
        return $this->belongsTo(Char::class);
    }

    public function itemDB()
    {
        return $this->belongsTo(ItemDB::class, 'nameid');
    }

}
