<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AccRegNum extends Model
{
    use HasFactory;
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $table = "acc_reg_num";
    protected $primaryKey = 'account_id';
    public $timestamps = false;

    protected $fillable = [
        'account_id',
        'key',
        'index',
        'value',
    ];

    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->table = "acc_reg_num_db";
        }
    }

    public function scopeFilter($query, $filterType, $class, $querySearch)
    {
        $classes = explode(',', $class);
        // dd($filterType);

        $rankTypes = [
            "JL" => "char.job_level",
            "BL" => "char.base_level",
            "A" => "acc_reg_num.value",
            "S" => "char.online",
        ];
        $type = $rankTypes[$filterType] ?? null;
        // dd($type);
        if (!in_array('All', $classes)) {
            $query = $query->whereIn('char.class', $classes);
        }

        if (!is_null($type)) {
            $query = $query->where($type, '>', 0);
        }

        $query = $query->where('char.name', 'like', '%' . $querySearch . '%');

        $query = $query->groupBy('account_id')
            ->orderByDesc($type);

        return $query;
    }
}
