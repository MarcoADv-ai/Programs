<?php
namespace App\Helpers\Logs;
use Illuminate\Support\Facades\Log;

class LogError implements LogType
{
    public function write($message)
    {
        Log::channel('xerror')->error($message);
    }

    public function writeInTable($data = [])
    {
        return;
    }
}