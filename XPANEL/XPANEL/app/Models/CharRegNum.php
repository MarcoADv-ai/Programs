<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class CharRegNum extends Model
{
    use HasFactory;
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $table = "char_reg_num";
    protected $primaryKey = 'char_id';
    public $timestamps = false;

    protected $fillable = [
        'char_id',
        'key',
        'index',
        'value',
    ];

    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->table = "char_reg_num_db";
        }
    }
}
