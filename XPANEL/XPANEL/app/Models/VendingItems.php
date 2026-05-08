<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class VendingItems extends Model
{
    use HasFactory;

    protected $table = 'vending_items';
    public $timestamps  = false;

    protected $fillable = [
        'vending_id',
        'index',
        'cartinventory_id',
        'amount',
        'price',
    ];
    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->table = 'autotrade_data';
            $this->fillable = [
                'char_id',
                'itemkey',
                'amount',
                'price',
            ];
        }
    }

    public function vending()
    {
        return $this->belongsTo(Vendings::class, 'id');
    }

    public function cartInventory()
    {
        return $this->belongsTo(CartInventory::class, 'cartinventory_id');
    }
}
