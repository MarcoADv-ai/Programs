<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MercadoPagoProcess extends Model
{
    use HasFactory;

    protected $table = "x_mp_donations_log";
    public $timestamps = false;

    protected $fillable = ['id', 'preference_id', 'master_id', 'client_id', 'items', 'credits', 'payment_status', 'create_date', 'mc_gross', 'mc_fee', 'mc_currency', 'tax'];

    public function user()
    {
        return $this->belongsTo(User::class, 'master_id', 'id');
    }
}
