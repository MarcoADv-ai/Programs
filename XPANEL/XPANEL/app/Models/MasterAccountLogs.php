<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAccountLogs extends Model
{
    use HasFactory;
    protected $table = "x_master_accounts_log";
    public $timestamps = false;


    protected $fillable = ['master_id', 'date', "log_type", 'ip', "session"];
}
