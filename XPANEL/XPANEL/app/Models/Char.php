<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Char extends Model
{
    use HasFactory;

    protected $table = "char";
    protected $primaryKey = 'char_id';
    public $timestamps = false;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [];

    protected $fillable = [
        'char_id',
        'account_id',
        'char_num',
        'name',
        'class',
        'base_level',
        'job_level',
        'base_exp',
        'job_exp',
        'zeny',
        'str',
        'agi',
        'vit',
        'int',
        'dex',
        'luk',
        'pow',
        'sta',
        'wis',
        'spl',
        'con',
        'crt',
        'max_hp',
        'hp',
        'max_sp',
        'sp',
        'max_ap',
        'ap',
        'status_point',
        'skill_point',
        'trait_point',
        'option',
        'karma',
        'manner',
        'party_id',
        'guild_id',
        'pet_id',
        'homun_id',
        'elemental_id',
        'hair',
        'hair_color',
        'clothes_color',
        'body',
        'weapon',
        'shield',
        'head_top',
        'head_mid',
        'head_bottom',
        'robe',
        'last_map',
        'last_x',
        'last_y',
        'save_map',
        'save_x',
        'save_y',
        'partner_id',
        'online',
        'father',
        'mother',
        'child',
        'fame',
        'rename',
        'delete_date',
        'moves',
        'unban_time',
        'font',
        'uniqueitem_counter',
        'sex',
        'hotkey_rowshift',
        'hotkey_rowshift2',
        'clan_id',
        'last_login',
        'title_id',
        'show_equip',
        'inventory_slots',
    ];

    public function __construct(array $attributes = [])
    {
        $config = Config::get('xpanel');
        parent::__construct($attributes);

        if ($config['emulator'] == 'Hercules') {
            $this->fillable = [
                'char_id',
                'account_id',
                'char_num',
                'name',
                'class',
                'base_level',
                'job_level',
                'base_exp',
                'job_exp',
                'zeny',
                'str',
                'agi',
                'vit',
                'int',
                'dex',
                'luk',
                'max_hp',
                'hp',
                'max_sp',
                'sp',
                'status_point',
                'skill_point',
                'option',
                'karma',
                'manner',
                'party_id',
                'guild_id',
                'clan_id',
                'pet_id',
                'homun_id',
                'elemental_id',
                'hair',
                'hair_color',
                'clothes_color',
                'body',
                'weapon',
                'shield',
                'head_top',
                'head_mid',
                'head_bottom',
                'robe',
                'last_login',
                'last_map',
                'last_x',
                'last_y',
                'save_map',
                'save_x',
                'save_y',
                'partner_id',
                'online',
                'father',
                'mother',
                'child',
                'fame',
                'rename',
                'delete_date',
                'slotchange',
                'char_opt',
                'font',
                'unban_time',
                'uniqueitem_counter',
                'sex',
                'hotkey_rowshift',
                'hotkey_rowshift2',
                'title_id',
                'playtime',
                'inventory_size',
            ];
        }
    }

    public function gameAccount()
    {
        return $this->belongsTo(GameAccount::class);
    }

    public function inventoryCart()
    {
        return $this->hasMany(CartInventory::class, 'char_id');
    }

    public function mvpLog()
    {
        return $this->hasMany(MvpLog::class, 'kill_char_id', 'char_id');
    }

    public function vendings()
    {
        return $this->hasMany(Vendings::class, 'char_id');
    }

    public function scopeFilter($query, $filterType, $class, $querySearch)
    {
        $classes = explode(',', $class);
        $rankTypes = [
            "JL" => "char.job_level",
            "BL" => "char.base_level",
            "PT" => "char.playtime",
            "D" => "death_count",
        ];
        $type = $rankTypes[$filterType] ?? null;

        if (!in_array('All', $classes)) {
            $query = $query->whereIn('char.class', $classes);
        }

        $query = $query->where('char.name', 'like', '%' . $querySearch . '%');
        $query = $query->orderBy($type, 'DESC');

        return $query;
    }
}
