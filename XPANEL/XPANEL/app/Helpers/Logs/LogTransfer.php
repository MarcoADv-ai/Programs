<?php
namespace App\Helpers\Logs;

use App\Models\PaypalProccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogTransfer implements LogType
{
    public function write($message)
    {
        Log::channel('xdonations')->info($message);
    }

    public function writeInTable($data = [])
    {
        DB::table('x_transfer_log')->insert($data);
    }
}