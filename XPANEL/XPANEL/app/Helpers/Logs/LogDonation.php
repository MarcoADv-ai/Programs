<?php
namespace App\Helpers\Logs;

use App\Models\PaypalProccess;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class LogDonation implements LogType
{
    public function write($message)
    {
        Log::channel('xdonations')->info($message);
    }

    public function writeInTable($data = [])
    {
        PaypalProccess::create($data);
    }
}