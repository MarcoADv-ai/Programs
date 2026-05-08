<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Mail extends Model
{
    use HasFactory;
    protected $table = "mail";
    public $timestamps = false;
    protected $fillable = [
        'id',
        'send_name',
        'send_id',
        'dest_name',
        'dest_id',
        'title',
        'message',
        'time',
        'status',
        'zeny',
        'type',
    ];

    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->fillable = [
                'id',
                'send_name',
                'send_id',
                'dest_name',
                'dest_id',
                'title',
                'message',
                'time',
                'status',
                'zeny',
                'nameid',
                'amount',
                'refine',
                'grade',
                'attribute',
                'identify',
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
                'unique_id',
            ];
        }
    }
}
