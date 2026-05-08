<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaypalProccess extends Model
{
    use HasFactory;
    protected $table = "x_paypal_donations_log";
    public $timestamps = false;


    protected $fillable = ['id', 'master_id', 'server_name', 'credits', 'bonus', 'items', 'payment_status', 'pending_reason', 'payment_date', 'mc_gross', 'mc_fee', 'mc_currency', 'txn_id', 'txn_type', 'first_name', 'last_name', 'payer_email', 'payer_status'];


    public function user()
    {
        return $this->belongsTo(User::class, 'master_id', 'id');
    }

    public function scopeFilter($query, $querySearch)
    {
        $query = $query->where('txn_id', 'like', '%' . $querySearch . '%');
        $query = $query->orderBy('id', 'DESC');

        return $query;
    }
}
