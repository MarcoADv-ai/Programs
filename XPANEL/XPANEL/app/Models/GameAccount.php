<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class GameAccount extends Model
{
    use HasFactory;

    protected $table = "login";
    protected $primaryKey = 'account_id';
    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function chars()
    {
        return $this->hasMany(Char::class);
    }

    public function vendings()
    {
        return $this->hasMany(Vendings::class);
    }

    public function scopeFilter($query, $filters)
    {
        $query = $query->where('userid', 'like', '%' . $filters . '%');
        $query = $query->orderBy('account_id', 'DESC');
    }

    protected $fillable = [
        'userid',
        'user_pass',
        'sex',
        'email',
        'group_id',
        'state',
        'unban_time',
        'expiration_time',
        'logincount',
        'lastlogin',
        'last_ip',
        'birthdate',
        'character_slots',
        'pincode',
        'pincode_change',
        'vip_time',
        'old_group',
        'web_auth_token',
        'web_auth_token_enabled',
        'last_unique_id',
        'blocked_unique_id',
        'master_acc_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'user_pass',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $config = Config::get('xpanel');

        if ($config['emulator'] == 'Hercules') {
            $this->fillable = [
                'userid',
                'user_pass',
                'sex',
                'email',
                'group_id',
                'state',
                'unban_time',
                'expiration_time',
                'logincount',
                'lastlogin',
                'last_ip',
                'birthdate',
                'character_slots',
                'pincode',
                'pincode_change',
                'last_unique_id',       // Gepard Shield
                'blocked_unique_id',    // Gepard Shield
                'master_acc_id'         // xPanel
            ];
        }
    }
}
